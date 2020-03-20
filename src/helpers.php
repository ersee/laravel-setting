<?php

if (!function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        /**
         * @var \Ersee\LaravelSetting\SettingManager
         */
        $setting = app(\Ersee\LaravelSetting\SettingManager::class);

        if (null === $key) {
            return $setting;
        } elseif (is_array($key)) {
            $setting->set($key);

            return $setting;
        }

        return $setting->get($key, $default);
    }
}
