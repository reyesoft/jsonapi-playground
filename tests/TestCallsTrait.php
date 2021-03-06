<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestResponse;

trait TestCallsTrait
{
    /**
     * @param string $url
     *
     * @return Response
     */
    protected function callGet($url, array $parameters = []): TestResponse
    {
        $this->response = $this->call('GET', $url, $parameters, [], [], []);

        return $this->response;
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    protected function callDelete($url): TestResponse
    {
        $this->response = $this->call('DELETE', $url, [], [], [], []);

        return $this->response;
    }

    /**
     * @param string $url
     * @param string $content
     *
     * @return Response
     */
    protected function callPost($url, $content): TestResponse
    {
        $this->response = $this->call('POST', $url, $content);

        return $this->response;
    }

    /**
     * @param string $url
     * @param string $content
     *
     * @return Response
     */
    protected function callPatch($url, $content): TestResponse
    {
        $this->response = $this->call('PATCH', $url, $content, [], [], [], []);

        return $this->response;
    }

    public function assertResponseStatus($code = 200): void
    {
        $actual = $this->response->getStatusCode();

        if ($actual !== $code) {
            $content = $this->response->getContent();
            $this->fail(
                'Failed asserting response status code that ' . $actual .
                    ' is ' . $code . '.' . "\n" . substr($content, 0, 17200)
            );
        }

        $this->assertSame($code, $actual);
    }
}
