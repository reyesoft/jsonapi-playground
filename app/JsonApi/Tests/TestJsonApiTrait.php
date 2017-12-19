<?php

namespace App\JsonApi\Tests;

trait TestJsonApiTrait
{
    public function assertResponseJsonApiError($http_error_code = 400) {
        $this->assertResponseStatus($http_error_code);
        $data = json_decode($this->response->getContent(), true);
        // $this->assertArraySubset(['config' => ['key-a', 'key-b']], ['config' => ['key-a']]);
        // $this->assertArrayHasKey('errors', $data);
        $this->assertInternalType('array', $data['errors']);
    }
}
