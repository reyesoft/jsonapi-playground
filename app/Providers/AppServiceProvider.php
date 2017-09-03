<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /*
         * Laravel 5.4: Specified key was too long error
         * https://laravel-news.com/laravel-5-4-key-too-long-error
         */
        Schema::defaultStringLength(191);

        // is ajax
        if(env('APP_DEBUG') && !$this->isAjax()) {
            DB::listen(function ($query) {
                // Log::critical('TIME: ' . $time / 1000 . ' QUERY: ' . $sql);
                echo('QUERY: ' . $query->sql . ', values: ' . json_encode($query->bindings) . ', TIME: ' . $query->time / 1000) . PHP_EOL;
            });
        }
    }

    private function isAjax() {
        return empty($_SERVER['HTTP_CACHE_CONTROL']);
        // return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
