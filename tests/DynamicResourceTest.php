<?php

namespace Tests;

class DynamicResourceTest extends TestCase
{
    public function testDinamicAll(): void
    {
        foreach ($this->models as $resource => $value) {
            $this->call('GET', 'v2/' . $resource)
                ->assertJsonFragment(['type' => $resource]);
        }
    }

    public function testDinamicShow(): void
    {
        foreach ($this->models as $resource => $value) {
            $model = $this->models[$resource];
            $object = $model::first();
            $this->call('GET', 'v2/' . $resource . '/' . $object->id)
                ->assertJsonFragment(['type' => $resource]);
        }
    }

    public function testDinamicRelatedAll(): void
    {
        foreach ($this->relations as $resource => $value) {
            $model = $this->models[$resource];
            $object = $model::first();
            foreach ($value as $item) {
                $url = 'v2/' . $resource . '/' . $object->id . '/' . $item;
                $response = $this->call('GET', $url);
                $result = $object->{$item};
                if (($result instanceof \Illuminate\Database\Eloquent\Collection && $result->isEmpty()) || !$result) {
                    $response->assertJsonFragment(['data' => []]);
                } else {
                    $response->assertJsonFragment(['type' => ($this->alias[$item] ?? $item)]);
                }
            }
        }
    }
}
