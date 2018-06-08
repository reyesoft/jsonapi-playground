<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

use Reyesoft\JsonApi\Services\ArrayDataService;

/**
 * Example of Schema without model.
 */
class CountryService extends ArrayDataService
{
    protected static $countries = [
        'AR' => 'Argentina',
        'CO' => 'Colombia',
        'BO' => 'Bolivia',
        'AW' => 'Aruba',
    ];

    public function all() //: array
    {
        foreach (self::$countries as $key => $country) {
            yield Country::instance($key, $country);
        }
    }

    public function get(string $id = null): Country
    {
        return Country::instance($id, self::$countries[$id]);
    }

    public static function getRelatedData($relation, $resource, $includeRelationships, $relation_alias)
    {
        return Country::instance('AR', self::$countries['AR']);
    }
}
