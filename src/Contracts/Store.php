<?php

namespace Ersee\LaravelSetting\Contracts;

interface Store
{
    /**
     * 获取全部项目.
     *
     * @return array
     */
    public function all();

    /**
     * 获取项目.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * 存储项目.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set($key, $value);

    /**
     * 删除项项目.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget($key);
}
