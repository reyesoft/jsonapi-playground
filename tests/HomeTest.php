<?php

namespace tests;

class HomeTest extends TestCase
{
    public function testHomeShowApplicationName(): void
    {
        $response = $this->get('/');
        $this->assertContains(config('app.name'), $response->getContent());
    }
}
