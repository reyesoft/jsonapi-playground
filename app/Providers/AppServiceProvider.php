<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ((bool) config('app.debug') && !empty($_SERVER['HTTP_CACHE_CONTROL'])) {
            echo number_format(microtime(true) - LARAVEL_START, 2) . PHP_EOL;
            DB::listen(
                function ($query): void {
                    echo(number_format(microtime(true) - LARAVEL_START, 2) . ' TIME: ' . $query->time / 1000 .
                        ' QUERY: ' . $query->sql . ', values: ' . json_encode($query->bindings)) . PHP_EOL;
                }
            );
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
