<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * @codeCoverageIgnore Unnecessary time. Also, this command is used on runtests bash script.
 */
class DataResetCommand extends Command
{
    protected $signature = 'data:reset';

    protected $description = 'Reset all example data.';

    public function handle(): void
    {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
    }
}
