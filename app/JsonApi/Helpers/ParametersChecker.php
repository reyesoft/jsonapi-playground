<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Helpers;

use App\JsonApi\Core\SchemaProvider;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;

class ParametersChecker
{
    public static function checkOrFail(
            SchemaProvider $schema,
            EncodingParameters $parameters,
            bool $isACollection
    ): void {
        // more info
        // https://github.com/neomerx/json-api/wiki/Parsing-API-Parameters#validation-for-url-and-header-parameters
        $allowUnrecognised = false;
        $includePaths = $schema->includePaths ?? [];

        $fieldSetTypes = $schema->fieldSetTypes ?? [];
        $pagingParameters = $schema->isPaginable() && $isACollection ? ['number', 'size'] : [];

        $filteringParameters = $isACollection ? $schema->getFiltersArray() : [];
        $sortParameters = $isACollection ? $schema->getSortArray() : [];

        $factory = new Factory();
        $checker = $factory->createQueryChecker(
            $allowUnrecognised,
            $includePaths,
            $fieldSetTypes,
            $sortParameters,
            $pagingParameters,
            $filteringParameters
        );

        $checker->checkQuery($parameters);
    }
}
