<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (env('APP_DEBUG') && !empty($_SERVER['HTTP_CACHE_CONTROL'])) {
            echo number_format(microtime(true) - LARAVEL_START, 2) . PHP_EOL;
            DB::listen(function ($query) {
                echo(number_format(microtime(true) - LARAVEL_START, 2) . ' TIME: ' . $query->time / 1000 .
                        ' QUERY: ' . $query->sql . ', values: ' . json_encode($query->bindings)) . PHP_EOL;
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
