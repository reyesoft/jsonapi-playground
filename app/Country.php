<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

class Country extends \stdClass
{
    public static function instance($id, $name)
    {
        $country = new self();
        $country->id = $id;
        $country->name = $name;

        return $country;
    }
}
