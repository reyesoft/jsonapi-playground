<?php

namespace App\JsonApi\Services;

use App\JsonApi\Exceptions\ResourceValidationException;
use App\JsonApi\Helpers\ObjectsBuilder;
use ArrayAccess;
use Illuminate\Validation\ValidationException;

class EloquentObjectService extends ObjectService
{
    public function all(): array {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        return $objectbuilder->getObjects();
    }

    public function allRelated($builder): array {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);
        $objectbuilder->buildEloquentBuilder($builder);

        return $objectbuilder->getObjects();
    }

    public function get($id): ArrayAccess {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        return $objectbuilder->getObject($id);
    }

    public function create(array $data): ArrayAccess {
        $modelname = $this->jsonapirequesthelper->getSchema()->getModelName();
        $object = new $modelname();
        $this->fillAndSaveObject($object, $data);

        return $this->get($object->id);
    }

    public function update($id, array $data): ArrayAccess {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        $object = $objectbuilder->getObject($id);
        $this->fillAndSaveObject($object, $data);

        return $objectbuilder->getObject($id);
    }

    protected function fillAndSaveObject($object, array $data): bool {
        $object->fill($data['data']['attributes']);

        $schema = $this->jsonapirequesthelper->getSchema();
        $relations_schema = $schema->getRelationshipsSchema();

        // fill relationships
        foreach ($relations_schema as $alias => $relationship_schema) {
            if (!array_key_exists($alias, $data['data']['relationships']))
                continue;

            if (!array_key_exists('data', $data['data']['relationships'][$alias]))
                continue;

            $this->fillRelationship($relationship_schema, $object, $alias, $data);
        }

        try {
            return $object->save();
        } catch (ValidationException $e) {
            throw new ResourceValidationException($e->errors());
        } catch (\Exception $e) {
            throw new ResourceValidationException('NO SAVED create(): ' . $e->getMessage() . '. ' . get_class($e));
        }
    }

    private function fillRelationship(array $relationship_schema, $object, string $alias, array $data) {
        $relation_data = $data['data']['relationships'][$alias]['data'];
        if ($relationship_schema['hasMany']) {
            // @todo
            // $object->{$alias}()->detach();
        } else {
            if ($relation_data === null) {
                $object->{$alias}()->dissociate();
            }
            elseif ($relation_data['id'])
            {
                $object->{$alias}()->associate($relation_data['id']);
            } else {
                throw new Exception('Proccess fillRelationship() with `' . $relation_data . '` for `' . $alias . '` is not possible.');
            }
        }
    }

    public function delete($id): bool {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        $object = $objectbuilder->getObject($id);
        $object->delete();

        return true;
    }
}
