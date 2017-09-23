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
        foreach($this->relationshipsSchema as $relation_alias => $relation) {
            if ($relation['hasMany']) {
                $ret[$relation_alias] = [self::DATA => $object->$relation_alias];
            } else {
                $ret[$relation_alias] = $this->buildRelationship($object, $includeList, $relation['schema']::$model, $relation_alias);
            }
        }

        return $ret;
    }
}
