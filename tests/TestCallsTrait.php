<?php

namespace Tests;

trait TestCallsTrait
{
    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    protected function callGet($url, array $parameters = [])
    {
        $this->response = $this->call('GET', $url, $parameters, [], [], []);

        return $this->response;
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    protected function callDelete($url)
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
    protected function callPost($url, $content)
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
    protected function callPatch($url, $content)
    {
        $this->response = $this->call('PATCH', $url, $content, [], [], [], []);

        return $this->response;
    }

    /**
     * @param string $url
     * @param string $content
     *
     * @return Response
     */
    protected function callPut($url, $content)
    {
        $this->response = $this->call('PUT', $url, $content, [], [], [], []);

        return $this->response;
    }

    public function assertResponseStatus($code = 200)
    {
        $actual = $this->response->getStatusCode();

        if ($actual != $code) {
            $content = $this->response->getContent();
            $this->fail('Failed asserting response status code that ' . $actual . ' is ' . $code . '.' . "\n" . substr($content, 0, 17200));
        }

        return $this->assertEquals($code, $actual);
    }
}