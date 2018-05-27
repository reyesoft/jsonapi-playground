<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Entrypoints;

use App\Store;

class StoresTest extends BaseTestCase
{
    protected $layout = [
        'model' => Store::class,
        'type' => 'stores',
        'attributes' => [
            'name',
            'address',
            'created_by',
        ],
        'relationships' => [
            'photos' => 'photos',
            'books' => 'books',
        ],
    ];

    public function testStoreIndex(): void
    {
        $this->callGet('/v2/stores/');
        $this->assertResponseStatus();
    }

    public function testStoreCreate()
    {
        $resource = $this->newResource();
        $this->callPost('/v2/stores/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['name'], $result['data']['attributes']['name']);

        return $result['data']['id'];
    }

    /**
     * @depends testStoreCreate
     */
    public function testStoreGet($store_id): void
    {
        $this->callGet('/v2/stores/' . $store_id);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($result['data']['id'], $store_id);
    }

    /**
     * @depends testStoreCreate
     */
    public function testStoreUpdate($store_id): void
    {
        $resource = $this->newResource($store_id);
        $this->callPatch('/v2/stores/' . $store_id, $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['name'], $result['data']['attributes']['name']);
    }

    /**
     * Security test: update fillable on model, but dont show on schema.
     *
     * @depends testStoreCreate
     */
    public function testStoreUpdatePrivateData($store_id): void
    {
        $store = Store::find($store_id);
        $original_private_data = 'This data is private for JsonApi';
        $store->private_data = $original_private_data;
        $store->save();

        $resource = $this->newResource($store_id);
        $resource['data']['attributes']['private_data'] = $original_private_data . '-wrong-access';
        $this->callPatch('/v2/stores/' . $store_id, $resource);
        $this->assertResponseStatus(200);

        $store = Store::find($store_id);
        $this->assertSame($resource['data']['attributes']['name'], $store->name);
        $this->assertSame($store->private_data, $original_private_data);
    }

    /**
     * @depends testStoreCreate
     */
    public function testStoreDelete($store_id): void
    {
        Store::findOrFail($store_id);

        $this->callDelete('/v2/stores/' . $store_id);
        $this->assertResponseStatus(200);

        $this->expectException(\Exception::class);
        Store::findOrFail($store_id);
    }

    /**
     * Business rule.
     */
    public function testStoreAddressCanBeSetOnCreate()
    {
        $resource = $this->newResource();
        $resource['data']['attributes']['address'] = '742 Evergreen Terrace';
        $this->callPost('/v2/stores/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['address'], $result['data']['attributes']['address']);

        return $result['data']['id'];
    }

    /**
     * Business rule: stores.address can be only set on create, can't be updated. cru rule on schema.
     *
     * @depends testStoreAddressCanBeSetOnCreate
     */
    public function testStoreAddressCanNotBeSetOnUpdate(string $store_id): void
    {
        $original_address = '742 Evergreen Terrace';
        $new_address = '316 Pikeland Ave';

        $store = Store::find($store_id);
        $store->address = $original_address;
        $store->save();

        $resource = $this->newResource($store_id);
        $original_address = $resource['data']['attributes']['address'];
        $resource['data']['attributes']['address'] = $new_address;
        $this->callPatch('/v2/stores/' . $store_id, $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($original_address, $result['data']['attributes']['address']);
        $store = Store::find($store_id);
        $this->assertSame($original_address, $store->address);
        $this->assertNotSame($new_address, $result['data']['attributes']['address']);
    }

    /**
     * Business rule: stores.created_by can be only read. cru rule on schema.
     *
     * @depends testStoreAddressCanBeSetOnCreate
     */
    public function testStoreCreatedByCanBeRead(string $store_id): void
    {
        $this->callGet('/v2/stores/' . $store_id);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertNotEmpty($result['data']['attributes']['created_by']);
    }

    /**
     * Business rule: stores.created_by can be only read. cru rule on schema.
     */
    public function testStoreCreatedByCanNotBeSetOnCreate(): void
    {
        $resource = $this->newResource();
        $resource['data']['attributes']['created_by'] = '1';
        $this->callPost('/v2/stores/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertNotSame(
            $resource['data']['attributes']['created_by'],
            $result['data']['attributes']['created_by']
        );
    }

    /**
     * Business rule: stores.created_by can be only read. cru rule on schema.
     *
     * @depends testStoreAddressCanBeSetOnCreate
     */
    public function testStoreCreatedByCanNotBeSetOnUpdate(string $store_id): void
    {
        $store = Store::find($store_id);
        $original_created_by = $store->created_by;
        $new_created_by = $original_created_by + 1;

        $resource = $this->newResource($store_id);
        $resource['data']['attributes']['created_by'] = $new_created_by;
        $this->callPatch('/v2/stores/' . $store_id, $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($store->created_by, $result['data']['attributes']['created_by']);
        $store = Store::find($store_id);
        $this->assertSame($original_created_by, $store->created_by);
        $this->assertNotSame($new_created_by, $result['data']['attributes']['created_by']);
    }
}
