<?php

namespace Tests;

class DinamicResourceTest extends TestCase
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
                if (!in_array($item, ['author', 'serie', 'book'])) {
                    if (!$object->{$item}->isEmpty()) {
                        $this->call('GET', $url);
                        $item = $this->findAlias($item);
                        $this->seeJsonContains(['type' => $item]);
                    }
                } else {
                    if (!$object->{$item}) {
                        $this->call('GET', $url);
                        $item = $this->findAlias($item);
                        $this->seeJsonContains(['type' => $item]);
                    }
                }
            }
        }
    }
}
