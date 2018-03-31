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
use App\JsonApi\Helpers\ObjectsBuilder;
use ArrayAccess;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Validation\ValidationException;

class EloquentDataService extends DataService
{
    public function all(): array
    {
        $builder = new ObjectsBuilder(
            $this->jsonapirequest->getSchema(),
            $this->jsonapirequest->getSchema()->getModelInstance(),
            $this->jsonapirequest->getParameters()
        );

        return $builder->getObjects();
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

        $builder = new ObjectsBuilder(
            $this->jsonapirequest->getSchema(),
            $this->jsonapirequest->getSchema()->getModelInstance(),
            $this->jsonapirequest->getParameters()
        );
        $builder->buildEloquentBuilder($eloquent_builder);

        return $builder->getObjects();
    }

    public function get(string $id = null): ArrayAccess
    {
        $builder = new ObjectsBuilder(
            $this->jsonapirequest->getSchema(),
            $this->jsonapirequest->getSchema()->getModelInstance(),
            $this->jsonapirequest->getParameters()
        );

        return $builder->getObject($id ?? $this->jsonapirequest->getId());
    }

    public function create(): ArrayAccess
    {
        $schema = $this->jsonapirequest->getSchema();
        $object = $this->jsonapirequest->getSchema()->getModelInstance();
        $schema->modelBeforeSave($object);
        $this->fillAndSaveObject($object, $this->jsonapirequest->getData());

        return $this->get((string) $object->id);
    }

    public function update(string $id = null): ArrayAccess
    {
        $schema = $this->jsonapirequest->getSchema();
        $builder = new ObjectsBuilder(
            $this->jsonapirequest->getSchema(),
            $this->jsonapirequest->getSchema()->getModelInstance(),
            $this->jsonapirequest->getParameters()
        );
        $object = $builder->getObject($id ?? $this->jsonapirequest->getId());
        $schema->modelBeforeSave($object);
        $this->fillAndSaveObject($object, $this->jsonapirequest->getData());

        return $this->get($id ?? $this->jsonapirequest->getId());
    }

    public function delete(string $id = null): bool
    {
        $schema = $this->jsonapirequest->getSchema();
        $builder = new ObjectsBuilder(
            $this->jsonapirequest->getSchema(),
            $this->jsonapirequest->getSchema()->getModelInstance(),
            $this->jsonapirequest->getParameters()
        );
        $object = $builder->getObject($id ?? $this->jsonapirequest->getId());
        $schema->modelBeforeSave($object);

        return $object->delete();
    }

    private function fillAndSaveObject($object, array $data): bool
    {
        $object->fill($data['data']['attributes']);

        $schema = $this->jsonapirequest->getSchema();
        $relations_schema = $schema->getRelationshipsSchema();

        // fill relationships
        foreach ($relations_schema as $alias => $relationship_schema) {
            if (!array_key_exists($alias, $data['data']['relationships'])) {
                continue;
            }

            if (!array_key_exists('data', $data['data']['relationships'][$alias])) {
                continue;
            }

            $this->fillRelationship($relationship_schema, $object, $alias, $data);
        }

        try {
            return $object->save();
        } catch (ValidationException $e) {
            throw new ResourceValidationException($e->errors());
        } catch (\Exception $e) {
            throw new ResourceValidationException(['NO SAVED resource: ' . $e->getMessage() . '. ' . get_class($e)]);
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
                throw new \Exception('Proccess hasOne fillRelationship() with `' .
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
                throw new \Exception('Proccess hasMany fillRelationship() with `' .
                    str_replace('"', '\'', json_encode($relation_data)) . '` for `' . $alias .
                    '` is not possible (' . $data['data']['type'] . '->' . $alias . ')'
                );
            }
        }
    }

    private function syncAllRelated($model_relation, $ids): void
    {
        // if ($model_relation instanceof \Illuminate\Database\Eloquent\Relations\MorphMany) {
        // @todo is not saving morphed relationships
        // $model_relation->saveMany($arrays_of_models);
        // } else {
        $model_relation->sync($ids);
        // }
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
}
