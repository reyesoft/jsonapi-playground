<?php

namespace Tests\Entrypoints;

use App\Author;

class NonExistentResourceTest extends BaseTestCase
{
    protected $layout = [
        'model' => Author::class,
        'type' => 'authors',
        'attributes' => [
            'name',
        ],
        'relationships' => [
            //'pricelist_categories' => 'pricelist_categories',
        ],
    ];

    public function testNonExistentResourceIndex(): void
    {
        $this->callGet('/v2/non-existent-resource/');
        $this->assertResponseJsonApiError(null, 404);
    }

    public function testNonExistentResourceShow(): void
    {
        $this->callGet('/v2/non-existent-resource/1/');
        $this->assertResponseJsonApiError(null, 404);
    }

    /*public function testBadUrlRequest()
    {
        $this->callGet('/v2/non-existent-resource/xxx/yyy/ooo/');
        $this->assertResponseJsonApiError();
    }*/
}
