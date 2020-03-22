<?php

namespace Ersee\LaravelSetting\Tests;

use Ersee\LaravelSetting\Facades\Setting;
use Ersee\LaravelSetting\Providers\SettingServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * 获取包提供者列表.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SettingServiceProvider::class,
        ];
    }

    /**
     * 获取包别名列表.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Setting' => Setting::class,
        ];
    }
}
