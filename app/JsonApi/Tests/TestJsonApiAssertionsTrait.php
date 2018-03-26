<?php

namespace App\JsonApi\Tests;

trait TestJsonApiAssertionsTrait
{
    use LumenCompatibilityTrait;

    public function assertResponseJsonApiError(string $expected_error_text = null, $http_error_code = 400): void
    {
        $this->assertResponseStatus($http_error_code);
        $this->assertJsonStructure([
            'errors' => [
                0 => [],
            ],
        ]);

        // expected error text
        if ($expected_error_text !== null) {
            $this->assertContains($expected_error_text, $this->response->getContent());
        } else {
            $this->assertNotContains('RouteCollection.php', $this->response->getContent());
        }
    }

    /**
     * @deprecated
     */
    public function assertResponseOk(): void
    {
        $this->assertResponseStatus(200);
    }

    public function assertReponseJsonApiCollection(): void
    {
        $this->assertResponseStatus(200);
        $this->response->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'type',
                    'attributes',
                ],
            ],
        ]);
    }

    public function assertResponseJsonApiResource(): void
    {
        $this->assertResponseStatus(200);
        $this->assertResponseJsonApiResourceStructure();
    }

    public function assertResponseJsonApiCreated(): void
    {
        $this->assertResponseStatus(201);
        $this->assertResponseJsonApiResourceStructure();
    }

    private function assertResponseJsonApiResourceStructure(): void
    {
        $this->response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes',
            ],
        ]);
    }

    public function assertResponseJsonApiDeleted(): void
    {
        switch ($this->response->getStatusCode()) {
            case 204:
                $this->assertResponseStatus(204);
                $this->assertEmpty($this->response->getContent());
                break;
            case 200:
                $this->assertResponseStatus(200);
                /*
                // http://jsonapi.org/format/#crud-deleting
                $this->assertNotEmpty($this->response->getContent());
                $this->assertJsonFragment(
                    ['status' => 'success'],
                    $this->response->getContent()
                );
                */
                break;
            default:
                $this->fail('Wrong response for resource deletion (Status code: '
                        . $this->response->getStatusCode() . ').');
        }
    }
}
