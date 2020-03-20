<?php

namespace Ersee\LaravelSetting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ersee\LaravelSetting\Contracts\Repository store(string|null $name = null)
 * @method static array all()
 * @method static bool has(string $key)
 * @method static mixed get(array|string $string, mixed $default = null)
 * @method static bool set(array|string $key, mixed $value = null)
 * @method static int|bool increment(string $key, int $value = 1)
 * @method static int|bool decrement(string $key, int $value = 1)
 * @method static int|bool forget(array|string $key)
 * @method static \Ersee\LaravelSetting\Contracts\Store getStore()
 *
 * @see \Ersee\LaravelSetting\SettingManager
 * @see \Ersee\LaravelSetting\Repository
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Ersee\LaravelSetting\SettingManager::class;
    }
}
