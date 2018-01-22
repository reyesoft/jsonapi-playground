<?php

namespace App\JsonApi\Tests;

trait TestJsonApiAssertionsTrait
{
    use LumenCompatibilityTrait;

    public function assertResponseJsonApiError($http_error_code = 400)
    {
        $this->assertResponseStatus($http_error_code);
        $this->assertJsonStructure([
            'errors' => [
                0 => [],
            ],
        ]);
    }

    public function assertReponseJsonApiCollection($with_almost_an_element = true)
    {
        $this->assertResponseStatus(200);
        $this->response->assertJsonStructure([
            'data' => $with_almost_an_element ? [
                0 => [
                    'id',
                    'type',
                    'attributes',
                ],
            ] : [],
        ]);
    }

    public function assertResponseJsonApiResource()
    {
        $this->assertResponseStatus(200);
        $this->response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes',
            ],
        ]);
    }

    public function assertResponseJsonApiDeleted()
    {
        $this->assertResponseStatus(200);
        $this->response->assertJsonFragment([
            'status' => 'success',
        ]);
    }
}
