<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Features;

use App\Author;
use Tests\Entrypoints\BaseTestCase;

class BadRequestsTest extends BaseTestCase
{
    protected $layout = [
        'model' => Author::class,
        'type' => 'authors',
        'attributes' => [
            'name',
        ],
        'relationships' => [
        ],
    ];

    public function testNonExistentResourceIndex(): void
    {
        $this->callGet('/v2/non-existent-resource/');
        $this->assertResponseJsonApiError('resource don\'t exist.', 404);
    }

    public function testNonExistentResourceShow(): void
    {
        $this->callGet('/v2/non-existent-resource/1/');
        $this->assertResponseJsonApiError('resource don\'t exist.', 404);
    }

    public function testUnrecognizedParameterIndex(): void
    {
        $this->callGet('/v2/authors/?not_valid_parameter=2');
        $this->assertResponseJsonApiError('Parameter is not allowed');
    }

    public function testUnrecognizedParameterShow(): void
    {
        $this->callGet('/v2/authors/1/?not_valid_parameter=2');
        $this->assertResponseJsonApiError('Parameter is not allowed');
    }

    /*
     * @todo
     */
    /*
    public function testBadSortParameterIndex()
    {
        $this->callGet('/v2/authors/?sort=AA2');
        $this->assertResponseJsonApiError('Parameter is not allowed');
    }
    */

    /*
     * @todo
     */
    /*
    public function testBadIncludePathsIndex()
    {
        $this->callGet('/v2/non-existent-resource/xxx/yyy/ooo/');
        $this->assertResponseJsonApiError('eddd');
    }
    */

    /*
     * @todo
     */
    /*
    public function testBadIncludePathsShow()
    {
        $this->callGet('/v2/non-existent-resource/xxx/yyy/ooo/');
        $this->assertResponseJsonApiError('eddd');
    }
    */

    /*
     * @todo
     */
    /*
    public function testBadFilteringIndex()
    {
        $this->callGet('/v2/non-existent-resource/xxx/yyy/ooo/');
        $this->assertResponseJsonApiError('eddd');
    }
    */

    /*
     * @todo
     */
    /*
    public function testBadUrlIndex()
    {
        $this->callGet('/v2/non-existent-resource/xxx/yyy/ooo/');
        $this->assertResponseJsonApiError('eddd');
    }
    */
}
