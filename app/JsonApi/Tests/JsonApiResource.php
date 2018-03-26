<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Tests;

class JsonApiResource
{
    protected $id = '';
    protected $type = '';
    protected $attributes = [];
    protected $relationships = [];

    public function getArray(): array
    {
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

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function setRelationships(array $relationships): void
    {
        $this->relationships = $relationships;
    }
}
