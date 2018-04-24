<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Core;

use App\JsonApi\Exceptions\BaseException;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\ErrorCollection;
use Neomerx\JsonApi\Http\Query\BaseQueryParser;

// more info on https://github.com/neomerx/json-api/blob/v1.x/src/Http/Query/QueryParametersParser.php

class QueryParser extends BaseQueryParser
{
    public $allowUnrecognized = false;

    public function checkQuery(): void
    {
        $errors = new ErrorCollection();
        //        $this->checkIncludePaths($errors, $parameters);
        //        $this->checkFieldSets($errors, $parameters);
        //        $this->checkFiltering($errors, $parameters);
        $this->getSortParameters($errors);
        //        $this->checkPaging($errors, $parameters);
        $this->checkUnrecognized($errors);

        if ($errors->count() > 0) {
            throw new BaseException($errors, BaseException::HTTP_CODE_BAD_REQUEST);
        }
    }

    public function getFiltering(): array
    {
        return $this->getParamArrayOrFail(self::PARAM_FILTER);
    }

    /**
     * @todo usar el mismo array de errores
     */
    private function getSortParameters($errors): void
    {
        $sortParams = null;
        $sortParam = $this->getParamStringOrFail(self::PARAM_SORT);
        if ($sortParam !== '') {
            foreach (explode(',', $sortParam) as $param) {
                // @todo compare with schema accepted attributes for sort
                if (empty($param) === true) {
                    $detail = 'Parameter ' . self::PARAM_SORT . ' should have valid value specified.';
                    throw new BaseException($this->createInvalidQueryErrors($detail));
                }
                $isDesc = ($param[0] === '-');
                $sortField = ltrim($param, '+-');
                if (empty($sortField) === true) {
                    $detail = 'Parameter ' . self::PARAM_SORT . ' should have valid name specified.';
                    throw new BaseException($this->createInvalidQueryErrors($detail));
                }
                // $sortParams[] = $this->factory->createSortParam($sortField, $isDesc === false);
            }
        }
        // return $sortParams;
    }

    private function getParamStringOrFail($name): string
    {
        $value = $this->getParameters()[$name] ?? '';
        if (is_string($value)) {
            return $value;
        }

        throw new BaseException(
            $this->createInvalidQueryErrors($name . ' param: Value should be either an string or null.')
        );
    }

    private function getParamArrayOrFail($name): array
    {
        $value = $this->getParameters()[$name] ?? [];
        if (is_array($value)) {
            return $value;
        }

        throw new BaseException(
            $this->createInvalidQueryErrors($name . ' param: Value should be either an array or null.')
        );
    }

    protected function createParamErrors($name, $detail)
    {
        // NOTE: external libraries might expect this method to exist and have certain signature
        // @see https://github.com/neomerx/json-api/issues/185#issuecomment-329135390

        $title = T::t('Invalid query parameter.');
        $source = [
            Error::SOURCE_PARAMETER => $name,
        ];

        return [
            new Error(null, null, null, null, $title, $detail, $source),
        ];
    }

    private function checkUnrecognized(ErrorCollection $errors): void
    {
        if ($this->allowUnrecognized) {
            return;
        }
        $unrecognizedParameters = $this->getUnrecognizedParameters($this->getParameters());

        if (empty($unrecognizedParameters)) {
            return;
        }

        foreach ($unrecognizedParameters as $name => $value) {
            /*  @todo translator T::t / class Translator implements TranslatorInterface */
            $errors->addQueryParameterError($name, 'Parameter is not allowed.');
        }
    }

    private function getUnrecognizedParameters(array $parameters)
    {
        $supported = [
            self::PARAM_INCLUDE => 0,
            self::PARAM_FIELDS => 0,
            self::PARAM_PAGE => 0,
            self::PARAM_FILTER => 0,
            self::PARAM_SORT => 0,
        ];
        $unrecognized = array_diff_key($parameters, $supported);

        return empty($unrecognized) === true ? null : $unrecognized;
    }

    protected function createInvalidQueryErrors($detail)
    {
        // NOTE: external libraries might expect this method to exist and have certain signature
        // @see https://github.com/neomerx/json-api/issues/185#issuecomment-329135390

        return [
            new Error(null, null, null, null, 'Invalid query.', $detail),
        ];
    }
}
