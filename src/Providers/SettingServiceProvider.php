<?php

namespace Ersee\LaravelSetting\Providers;

use Ersee\LaravelSetting\Console\Commands\AllCommand;
use Ersee\LaravelSetting\Console\Commands\DecrementCommand;
use Ersee\LaravelSetting\Console\Commands\ForgetCommand;
use Ersee\LaravelSetting\Console\Commands\GetCommand;
use Ersee\LaravelSetting\Console\Commands\IncrementCommand;
use Ersee\LaravelSetting\Console\Commands\SetCommand;
use Ersee\LaravelSetting\SettingManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/setting.php',
            'setting'
        );

        $this->app->singleton(SettingManager::class, function ($app) {
            return new SettingManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if ('database' === $this->app['config']['setting.default']) {
                $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
            }

            $this->publishes([
                __DIR__.'/../../config/setting.php' => config_path('setting.php'),
            ], 'setting-config');

            $this->commands([
                AllCommand::class,
                DecrementCommand::class,
                ForgetCommand::class,
                GetCommand::class,
                IncrementCommand::class,
                SetCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            SettingManager::class,
        ];
    }
}
