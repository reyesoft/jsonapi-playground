<?php

namespace Tests;

class DynamicResourceTest extends TestCase
{
    public function testDinamicAll()
    {
        foreach ($this->models as $resource => $value) {
            $this->call('GET', 'v2/' . $resource);
            $this->seeJsonContains(['type' => $resource]);
        }
    }

    public function testDinamicShow()
    {
        foreach ($this->models as $resource => $value) {
            $model = $this->models[$resource];
            $object = $model::first();
            $this->call('GET', 'v2/' . $resource . '/' . $object->id);
            $this->seeJsonContains(['type' => $resource]);
        }
    }

    public function testDinamicRelatedAll()
    {
        foreach ($this->relations as $resource => $value) {
            $model = $this->models[$resource];
            $object = $model::first();
            foreach ($value as $item) {
                $url = 'v2/' . $resource . '/' . $object->id . '/' . $item;
                $this->call('GET', $url);
                $result = $object->{$item};
                if (($result instanceof \Illuminate\Database\Eloquent\Collection && $result->isEmpty()) || !$result) {
                    $this->seeJsonContains(['data' => []]);
                } else {
                    $this->seeJsonContains(['type' => ($this->alias[$item] ?? $item)]);
                }
            }
        }
    }
}
