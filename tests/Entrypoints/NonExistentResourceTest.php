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

    public function testNonExistentResourceIndex()
    {
        $this->callGet('/v2/non-existent-resource/');
        $this->assertResponseJsonApiError();
    }

    public function testNonExistentResourceShow()
    {
        $this->callGet('/v2/non-existent-resource/1/');
        $this->assertResponseJsonApiError();
    }

    /*public function testBadUrlRequest()
    {
        $this->callGet('/v2/non-existent-resource/xxx/yyy/ooo/');
        $this->assertResponseJsonApiError();
    }*/
}
