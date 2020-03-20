<?php

namespace Ersee\LaravelSetting;

use Ersee\LaravelSetting\Contracts\Repository as RepositoryContract;
use Ersee\LaravelSetting\Contracts\Store;
use Ersee\LaravelSetting\Events\Forgotten;
use Ersee\LaravelSetting\Events\Hit;
use Ersee\LaravelSetting\Events\Missed;
use Ersee\LaravelSetting\Events\Written;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Traits\Macroable;

class Repository implements \ArrayAccess, RepositoryContract
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * 存储实例.
     *
     * @var \Ersee\LaravelSetting\Contracts\Store
     */
    protected $store;

    /**
     * 事件分发实例.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * 创建存储库实例.
     *
     * @param \Ersee\LaravelSetting\Contracts\Store $store
     *
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * 获取全部项目.
     *
     * @return array
     */
    public function all()
    {
        return $this->store->all();
    }

    /**
     * 判断项目是否存在.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return null !== $this->get($key);
    }

    /**
     * 获取项目.
     *
     * @param array|string $key
     * @param mixed        $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (\is_array($key)) {
            return $this->getMany($key);
        }

        $value = $this->store->get($key);

        if (null === $value) {
            $this->event(new Missed($key));

            $value = value($default);
        } else {
            $this->event(new Hit($key, $value));
        }

        return $value;
    }

    /**
     * 获取多个项目.
     *
     * @param array $keys
     *
     * @return array
     */
    protected function getMany(array $keys)
    {
        $values = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $values[$key] = $this->get($key, $default);
        }

        return $values;
    }

    /**
     * 存储项目.
     *
     * @param array|string $key
     * @param mixed        $value
     *
     * @return bool
     */
    public function set($key, $value = null)
    {
        if (\is_array($key)) {
            return $this->setMany($key);
        }

        if (null === $value) {
            return false;
        }

        return tap($this->store->set($key, $value), function ($result) use ($key, $value) {
            if ($result) {
                $this->event(new Written($key, $value));
            }
        });
    }

    /**
     * 设置多个项目.
     *
     * @param array $keys
     *
     * @return bool
     */
    protected function setMany(array $keys)
    {
        $result = true;

        foreach ($keys as $key => $value) {
            if (is_numeric($key)) {
                [$key, $value] = [$value, null];
            }

            if (!$this->set($key, $value)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * 增加项目值.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        return $this->incrementOrDecrement($key, $value, function ($current, $value) {
            return $current + $value;
        });
    }

    /**
     * 减小项目值.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        return $this->incrementOrDecrement($key, $value, function ($current, $value) {
            return $current - $value;
        });
    }

    /**
     * 增加或减少项目值.
     *
     * @param string   $key
     * @param mixed    $value
     * @param \Closure $callback
     *
     * @return int|bool
     */
    protected function incrementOrDecrement($key, $value, \Closure $callback)
    {
        $current = $this->get($key);

        if (null === $current || !is_numeric($current)) {
            return false;
        }

        $new = $callback((int) $current, $value);

        $this->set($key, $new);

        return $new;
    }

    /**
     * 删除项目.
     *
     * @param array|string $key
     *
     * @return bool
     */
    public function forget($key)
    {
        if (\is_array($key)) {
            return $this->forgetMany($key);
        }

        return tap($this->store->forget($key), function ($result) use ($key) {
            if ($result) {
                $this->event(new Forgotten($key));
            }
        });
    }

    /**
     * 删除多个项目.
     *
     * @param array $keys
     *
     * @return bool
     */
    protected function forgetMany(array $keys)
    {
        $result = true;

        foreach ($keys as $key) {
            if (!$this->forget($key)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * 获取存储实例.
     *
     * @return \Ersee\LaravelSetting\Contracts\Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * 触发事件.
     *
     * @param string $event
     *
     * @return void
     */
    protected function event($event)
    {
        if (isset($this->events)) {
            $this->events->dispatch($event);
        }
    }

    /**
     * 获取事件分发实例.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->events;
    }

    /**
     * 设置事件分发实例.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function setEventDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * 判断项目是否存在.
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * 获取项目.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * 存储项目.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * 删除项目.
     *
     * @param string $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->forget($key);
    }

    /**
     * 动态调用存储库实例方法.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->store->$method(...$parameters);
    }

    /**
     * 克隆存储库实例.
     *
     * @return void
     */
    public function __clone()
    {
        $this->store = clone $this->store;
    }
}
