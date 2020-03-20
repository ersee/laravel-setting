<?php

namespace Ersee\LaravelSetting;

use Ersee\LaravelSetting\Contracts\Store;
use Ersee\LaravelSetting\Stores\DatabaseStore;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class SettingManager
{
    /**
     * 应用程序实例.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * 已解析的驱动程序.
     *
     * @var array
     */
    protected $stores = [];

    /**
     * 已注册的自定义驱动程序.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * 创建管理器实例.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 获取指定名称驱动程序实例.
     *
     * @param string|null $name
     *
     * @return \Ersee\LaravelSetting\Contracts\Repository
     */
    public function store($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->stores[$name] = $this->get($name);
    }

    /**
     * 获取驱动程序实例.
     *
     * @param string|null $driver
     *
     * @return \Ersee\LaravelSetting\Contracts\Repository
     */
    public function driver($driver = null)
    {
        return $this->store($driver);
    }

    /**
     * 获取驱动程序实例.
     *
     * @param string $name
     *
     * @return \Ersee\LaravelSetting\Contracts\Repository
     */
    protected function get($name)
    {
        return $this->stores[$name] ?? $this->resolve($name);
    }

    /**
     * 解析指定驱动程序实例.
     *
     * @param string $name
     *
     * @return \Ersee\LaravelSetting\Contracts\Repository
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (null === $config) {
            throw new \InvalidArgumentException("Setting store [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new \InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
    }

    /**
     * 调用自定义驱动程序实例.
     *
     * @param array $config
     *
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    /**
     * 创建数据库驱动程序实例.
     *
     * @param array $config
     *
     * @return \Ersee\LaravelSetting\Contracts\Repository
     */
    protected function createDatabaseDriver(array $config)
    {
        $connection = $this->app['db']->connection($config['connection'] ?? null);

        return $this->repository(
            new DatabaseStore($connection, $config['table'])
        );
    }

    /**
     * 创建存储库.
     *
     * @param \Ersee\LaravelSetting\Contracts\Store $store
     *
     * @return \Ersee\LaravelSetting\Contracts\Repository
     */
    public function repository(Store $store)
    {
        return tap(new Repository($store), function ($repository) {
            $this->setEventDispatcher($repository);
        });
    }

    /**
     * 设置驱动程序实例事件分发.
     *
     * @param \Ersee\LaravelSetting\Contracts\Repository $repository
     *
     * @return void
     */
    protected function setEventDispatcher(Repository $repository)
    {
        if (!$this->app->bound(DispatcherContract::class)) {
            return;
        }

        $repository->setEventDispatcher(
            $this->app[DispatcherContract::class]
        );
    }

    /**
     * 重新设置驱动程序实例事件分发.
     *
     * @return void
     */
    public function refreshEventDispatcher()
    {
        array_map([$this, 'setEventDispatcher'], $this->stores);
    }

    /**
     * 获取驱动程序连接配置.
     *
     * @param string $name
     *
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["setting.stores.{$name}"];
    }

    /**
     * 获取默认驱动程序名称.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['setting.default'];
    }

    /**
     * 设置默认驱动程序名称.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['setting.default'] = $name;
    }

    /**
     * 取消设置指定的驱动程序实例.
     *
     * @param array|string|null $name
     *
     * @return $this
     */
    public function forgetDriver($name = null)
    {
        $name = $name ?? $this->getDefaultDriver();

        foreach ((array) $name as $driver) {
            if (isset($this->stores[$driver])) {
                unset($this->stores[$driver]);
            }
        }

        return $this;
    }

    /**
     * 注册自定义驱动程序.
     *
     * @param string   $driver
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend($driver, Closure $callback)
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * 动态调用默认驱动程序实例方法.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->store()->$method(...$parameters);
    }
}
