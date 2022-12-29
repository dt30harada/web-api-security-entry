<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();

        if (config('app.debug')) {
            $this->enableLoggingSql();
        }
    }

    /**
     * 実行SQLのログ出力を有効化
     *
     * @return void
     */
    private function enableLoggingSql(): void
    {
        DB::listen(function ($query) {
            Log::debug('SQL LOG::', [
                $query->sql,
                $query->bindings,
                $query->time,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
