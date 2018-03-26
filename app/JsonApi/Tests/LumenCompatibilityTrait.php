<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Tests;

// Via https://github.com/itgalaxy/laravel-foundation/blob/master/Testing/TestResponse.php

trait LumenCompatibilityTrait
{
    /**
     * Assert that the response has a given JSON structure.
     *
     * @param array|null $structure
     * @param array|null $responseData
     *
     * @return $this
     */
    public function assertJsonStructure(array $structure = null, $responseData = null)
    {
        if (null === $structure) {
            return $this->assertJson($this->json());
        }
        if (null === $responseData) {
            $responseData = $this->decodeResponseJson();
        }
        foreach ($structure as $key => $value) {
            if (is_array($value) && $key === '*') {
                $this->assertInternalType('array', $responseData);
                foreach ($responseData as $responseDataItem) {
                    $this->assertJsonStructure($structure['*'], $responseDataItem);
                }
            } elseif (is_array($value)) {
                $this->assertArrayHasKey($key, $responseData);
                $this->assertJsonStructure($structure[$key], $responseData[$key]);
            } else {
                $this->assertArrayHasKey($value, $responseData);
            }
        }

        return $this;
    }

    /**
     * Validate and return the decoded response JSON.
     *
     * @return array
     */
    public function decodeResponseJson()
    {
        $decodedResponse = json_decode($this->response->getContent(), true);
        if (null === $decodedResponse || $decodedResponse === false) {
            if ($this->exception) {
                throw $this->exception;
            } else {
                $this->fail('Invalid JSON was returned from the route.');
            }
        }

        return $decodedResponse;
    }

    public function assertJsonFragment(array $expected): void
    {
        $data = json_decode($this->response->getContent(), true);
        $this->assertArraySubset($expected, $data);
    }
}
