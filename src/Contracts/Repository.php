<?php

namespace Ersee\LaravelSetting\Contracts;

interface Repository
{
    /**
     * 获取全部项目.
     *
     * @return array
     */
    public function all();

    /**
     * 判断项目是否存在.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * 获取项目.
     *
     * @param array|string $key
     * @param mixed        $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * 存储项目.
     *
     * @param array|string $key
     * @param mixed        $value
     *
     * @return bool
     */
    public function set($key, $value = null);

    /**
     * 增加项目值.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int|bool
     */
    public function increment($key, $value = 1);

    /**
     * 减小项目值.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int|bool
     */
    public function decrement($key, $value = 1);

    /**
     * 删除项目.
     *
     * @param array|string $key
     *
     * @return bool
     */
    public function forget($key);

    /**
     * 获取存储实例.
     *
     * @return \Ersee\LaravelSetting\Contracts\Store
     */
    public function getStore();
}
