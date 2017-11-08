<?php

namespace tests;

class HomeTest extends TestCase
{
    public function testHomeShowApplicationName()
    {
        $this->get('/');
        $this->assertContains(
            env('APP_NAME'), $this->response->getContent()
        );
    }
}
