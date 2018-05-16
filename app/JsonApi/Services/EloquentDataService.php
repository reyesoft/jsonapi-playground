<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Services;

use App\JsonApi\Exceptions\ResourceValidationException;
use ArrayAccess;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EloquentDataService extends DataService
{
    public static $transactionOpened = false;

    public function all(): array
    {
        return $this->getObjectBuilder()->getObjects();
    }

    public function related(): array
    {
        // find parent resource
        $parent_schema_class = $this->jsonapirequest->getParentSchemaClass();
        $parent_schema = new $parent_schema_class();

        // set child model
        $parent_model_class = $parent_schema->getModelName();
        $parent_model = $parent_model_class::findOrFail($this->jsonapirequest->getParentId());
        $eloquent_builder = $parent_model->{$this->jsonapirequest->getResourceAlias()}();

        $builder = $this->getObjectBuilder();
        $builder->buildEloquentBuilder($eloquent_builder);

        return $builder->getObjects();
    }

    public function get(string $id = null): ArrayAccess
    {
        $builder = $this->getObjectBuilder();

        return $builder->getObject($id ?? $this->action->getId());
    }

    public function create(): ArrayAccess
    {
        $schema = $this->action->getSchema();
        $object = $this->action->getSchema()->getModelInstance();
        $schema->modelBeforeSave($object);
        $this->applyModelPolicy($object);
        $this->fillAndSaveObject($object, $this->action->getData());

        return $this->get((string) $object->id);
    }

    public function update(string $id = null): ArrayAccess
    {
        $schema = $this->action->getSchema();
        $builder = $this->getObjectBuilder();
        $object = $builder->getObject($id ?? $this->action->getId());
        $schema->modelBeforeSave($object);
        $this->applyModelPolicy($object);
        $this->fillAndSaveObject($object, $this->action->getData());

        return $this->get($id ?? $this->action->getId());
    }

    public function delete(string $id = null): bool
    {
        $schema = $this->action->getSchema();
        $builder = $this->getObjectBuilder();
        $object = $builder->getObject($id ?? $this->action->getId());
        $schema->modelBeforeSave($object);
        $this->applyModelPolicy($object);

        return $object->delete();
    }

    protected function applyModelPolicy($model): void
    {
        $schema = $this->action->getSchema();
        $policy = $schema->getPolicy();
        if (!$policy->model($model) || !$policy->{ 'model' . ucfirst($this->action->getActionName()) }($model)) {
            throw new ResourcePolicyException($this->action->getActionName());
        }
    }

    private function beginTransaction(): void
    {
        if (!self::$transactionOpened) {
            DB::beginTransaction();
            self::$transactionOpened = true;
        }
    }

    private function fillAndSaveObject($object, array $data): bool
    {
        $object->fill($data['data']['attributes']);

        $schema = $this->action->getSchema();
        $relations_schema = $schema->getRelationshipsSchema() ?? [];

        // this is required?
        if ($this->action->isSaving()) {
            $this->sortRelationsBySchemaHasMany($relations_schema);
        }

        // fill relationships
        foreach ($relations_schema as $alias => $relationship_schema) {
            if (!array_key_exists('relationships', $data['data'])) {
                break;
            }

            if (!array_key_exists($alias, $data['data']['relationships'])) {
                continue;
            }

            if (!array_key_exists('data', $data['data']['relationships'][$alias])) {
                continue;
            }

            $this->beginTransaction();
            if (!$object->id && $relationship_schema['hasMany']) {
                // save children requires a parent with id
                if (!$this->saveObject($object)) {
                    return false;
                }
            }

            $this->fillRelationship($relationship_schema, $object, $alias, $data);
        }

        if ($this->saveObject($object)) {
            DB::commit();

            return true;
        }
        DB::rollback();

        return false;
    }

    /**
     * This is required because we need save hasOne relations first.
     * Next, object; and finally hasMany relations.
     *
     * @return array
     */
    private function sortRelationsBySchemaHasMany(array &$relations_schema): void
    {
        uasort($relations_schema, function ($a, $relationship_schema) {
            return $relationship_schema['hasMany'] ? -1 : 1;
        });
    }

    private function saveObject($object): bool
    {
        try {
            $object->save();
            DB::commit();

            return true;
        } catch (ValidationException $e) {
            DB::rollback();
            throw new ResourceValidationException($e->errors());
        } catch (\Exception $e) {
            // Personalized validation. For example BaseException on Apicultor project.
            DB::rollback();
            throw $e;
        }
    }

    private function fillRelationship(array $relationship_schema, $object, string $alias, array $data): void
    {
        $relation_data = $data['data']['relationships'][$alias]['data'];
        if (!$relationship_schema['hasMany']) {
            // hasOne
            if ($relation_data === null) {
                $object->{$alias}()->dissociate();
            } elseif ($relation_data === []) {
                // do nothing :/
                return;
            } elseif ($relation_data['id']) {
                $object->{$alias}()->associate($relation_data['id']);
            } else {
                throw new \Exception('Process hasOne fillRelationship() with `' .
                    str_replace('"', '\'', json_encode($relation_data)) . '` for `' . $alias .
                    '` is not possible (' . $data['data']['type'] . '->' . $alias . ')'
                );
            }
        } else {
            // hasMany
            if (count($relation_data) === 0 && !isset($data['data']['id'])) {
                return;
            }

            if (count($relation_data) === 0) {
                $this->removeAllRelated($object->{$alias}());
            } elseif (count($relation_data) > 0) {
                $ids = $this->getIdsFromDataCollection($relation_data);
                $this->syncAllRelated($object->{$alias}(), $ids);
            } else {
                throw new \Exception('Process hasMany fillRelationship() with `' .
                    str_replace('"', '\'', json_encode($relation_data)) . '` for `' . $alias .
                    '` is not possible (' . $data['data']['type'] . '->' . $alias . ')'
                );
            }
        }
    }

    private function syncAllRelated($model_relation, array $ids): void
    {
        if ($model_relation instanceof HasMany
            || $model_relation instanceof MorphMany
        ) {
            foreach ($ids as $id) {
                $model_relation->save($model_relation->getModel()::find($id));
            }
        } else {
            $model_relation->sync($ids);
        }
    }

    private function removeAllRelated($model_relation): void
    {
        if ($model_relation instanceof BelongsToMany) {
            $model_relation->detach();
        } else {
            $model_relation->delete();
        }
    }

    private function getIdsFromDataCollection($data_collection): array
    {
        return array_map(function ($data_resource) {
            return $data_resource['id'];
        }, $data_collection);
    }

    public function openTransaction(): void
    {
    }

    public function closeTransaction(): void
    {
    }
}
