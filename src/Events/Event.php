<?php

namespace Ersee\LaravelSetting\Events;

abstract class Event
{
    /**
     * 设置key.
     *
     * @var array|string
     */
    public $key;

    /**
     * 创建事件实例.
     *
     * @param array|string $key
     *
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
    }
}
