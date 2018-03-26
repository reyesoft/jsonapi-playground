<?php

namespace tests;

class HeadersTest extends TestCase
{
    public function testIfResponseWithCorrectHeader(): void
    {
        foreach ($this->models as $resource => $value) {
            $response = $this->call('GET', 'v2/' . $resource);
            $this->assertEquals('application/vnd.api+json', $response->headers->get('content-type'));
        }
    }
}
