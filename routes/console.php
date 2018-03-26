<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;

Artisan::command(
    'inspire', function (): void {
        $this->comment(Inspiring::quote());
    }
)->describe('Display an inspiring quote');
