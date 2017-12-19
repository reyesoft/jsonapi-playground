<?php
/**
 * Copyright (C) 1997-2017 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Multinexo. Multinexo can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace App\JsonApi\Tests;

class JsonApiResource
{
    protected $id = '';
    protected $type = '';
    protected $attributes = [];
    protected $relationships = [];

    public function getArray(): array {
        $array = [
            'data' => [
                'type' => $this->type,
                'attributes' => $this->attributes,
                'relationships' => $this->relationships ?? [],
            ],
        ];
        if ($this->id) {
            $array['data']['id'] = $this->id;
        }

        return $array;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setType(string $type) {
        $this->type = $type;
    }

    public function setAttributes(array $attributes) {
        $this->attributes = $attributes;
    }

    public function setRelationships(array $relationships) {
        $this->relationships = $relationships;
    }
}
