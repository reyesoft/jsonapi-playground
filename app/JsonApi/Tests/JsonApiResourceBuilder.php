<?php

namespace App\JsonApi\Tests;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class JsonApiResourceBuilder
{
    private $layout = [];

    public function __construct(array $layout)
    {
        $this->layout = $layout;
    }

    public function newResource($model_instance = null): array
    {
        if ($model_instance instanceof Model) {
            return $this->buildResourceFromModel($model_instance);
        } elseif (is_numeric($model_instance) || is_string($model_instance)) {
            return $this->buildResourceFromId($model_instance);
        } elseif ($model_instance === null) {
            return $this->buildResourceFromFactory();
        }

        throw new \Exception('No se reconoce el tipo $model_instance en TestCompanyCase buildResource.');
    }

    private function buildResourceFromModel(Model $modelInstance): array
    {
        $this->fixCarbonDatesW3c($modelInstance);

        return $this->buildResourceFromObject($modelInstance);
    }

    private function buildResourceFromId($model_id): array
    {
        $instance = $this->layout['model'];
        $modelInstance = $instance::find($model_id);

        return $this->buildResourceFromObject($modelInstance);
    }

    private function buildResourceFromFactory(): array
    {
        $objects = factory($this->layout['model'])->make();

        return $this->buildResourceFromObject($objects);
    }

    private function buildResourceFromObject(Model $object): array
    {
        $resource = new JsonApiResource();

        $resource->setId($object->id ?? '');
        $resource->setType($this->layout['type']);
        $resource->setAttributes($this->buildAttributes($object));
        $resource->setRelationships($this->buildRelationships($object));

        return $resource->getArray();
    }

    private function buildAttributes(Model $modelInstance): array
    {
        $ret = [];
        foreach ($this->layout['attributes'] as $key => $value) {
            if ($modelInstance->{$value} === null) {
                throw new \Exception('No se encontró `' . $value . '` en el modelo para el attribute `' . $key . '`.');
            }
            $ret[is_numeric($key) ? $value : $key] = $modelInstance->{$value};
        }

        return $ret;
    }

    private function buildRelationships(Model $modelInstance): array
    {
        $ret = [];
        foreach ($this->layout['relationships'] as $typealias => $type) {
            $modelMethod = $typealias ?? $type;
            $modelOrCollection = $modelInstance->{$modelMethod};
            if ($modelOrCollection instanceof Collection) {
                $ret[$typealias] = ['data' => $this->colletion2relationshipData($modelOrCollection)];
            } elseif ($modelOrCollection instanceof Model) {
                $ret[$typealias] = ['data' => $this->model2relationshipData($modelOrCollection, $type)];
            } elseif ($modelOrCollection === null) {
                // @todo, we need to know if is a Collection or a Resource
                $ret[$typealias] = ['data' => []];
                // $ret[$typealias] = ['data' => null];
            } else {
                throw new \Exception(
                        'El tipo `' . get_class($modelOrCollection) . '` retornado por `'
                        . get_class($modelInstance) . '::' . $modelMethod
                        . '` no es reconocible para crear la relationship `' . $typealias . '`.'
                    );
            }
        }

        return $ret;
    }

    private function model2relationshipData(Model $model, string $alias = null): array
    {
        return [
            'id' => $model->id,
            'type' => $alias ?? $model->type,    // @todo
            ];
    }

    private function colletion2relationshipData(Collection $collection): array
    {
        $ret = [];
        foreach ($collection as $model) {
            $ret[] = $this->model2relationshipData($model);
        }

        return $ret;
    }

    private function fixCarbonDatesW3c(Model $modelInstance)
    {
        /*
            SOLVE PROBLEM WITH
            InvalidArgumentException: Unexpected data found.
            Data missing

            /var/www/apicultor/vendor/nesbot/carbon/src/Carbon/Carbon.php:582
            /var/www/apicultor/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php:715
            /var/www/apicultor/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php:129
            /var/www/apicultor/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php:91
            /var/www/apicultor/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:941
            /var/www/apicultor/tests/TestCompanyCase.php:49
            /var/www/apicultor/tests/TestCompanyCase.php:35
            /var/www/apicultor/tests/PhysicalPosTests/PhysicalPosTest.php:126
         */
        $attributes = $modelInstance->getAttributes();

        // convert dates to w3c
        foreach ($attributes as $key => $value) {
            if (preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}.*[0-9]{2}/', $value)) {
                $modelInstance->{$key} = Carbon::parse($modelInstance->{$key});
            }
        }
    }
}
