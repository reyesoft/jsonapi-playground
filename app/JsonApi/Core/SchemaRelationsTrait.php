<?php

namespace App\JsonApi\Core;

trait SchemaRelationsTrait
{
    public function getRelationships($object, $isPrimary, array $includeList)
    {
        if (!$isPrimary) {
            return [];
        }

        // elements like
        // $rel[$relation_alias] = $this->buildRelationship($object, $includeList, '\App\Author', 'author');
        $ret = [];
        foreach(static::$relationships as $relation_alias => $relation) {
            if ($relation['hasMany']) {
                $ret[$relation_alias] = [self::DATA => $object->{$relation_alias}];
            } else {
                $ret[$relation_alias] = $this->buildRelationship($object, $includeList, $relation['schema']::$model, $relation_alias);
            }
        }

        return $ret;
    }

    protected function buildRelationship($object, array $includeList, $modelClass, $singularType)
    {
        if (isset($includeList[$singularType])) {
            $relation = $object->{$singularType};
        } else {
            $modelFieldId = $singularType . '_id';
            if ($object->{$modelFieldId} != 0) {
                $relation = new $modelClass();
                $relation->id = $object->{$modelFieldId};
            } else {
                // no element on this hasOne relationship
                // http://jsonapi.org/format/#fetching-resources-responses
                $relation = null;
            }
        }

        return [self::DATA => $relation];
    }
}
