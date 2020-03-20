<?php

namespace Ersee\LaravelSetting\Events;

class Written extends Event
{
    /**
     * 设置value.
     *
     * @var mixed
     */
    public $value;

    /**
     * 创建事件实例.
     *
     * @param array|string $key
     * @param mixed        $value
     *
     * @return void
     */
    public function __construct($key, $value)
    {
        parent::__construct($key);

        $this->value = $value;
    }
}
