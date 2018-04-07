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

class NotValidParametersTest extends BaseTestCase
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
        // @todo deberia devolver_error
        $this->callGet('/v2/stores/?page_wrong_parameter=2');
        $this->assertResponseStatus();
    }
}
