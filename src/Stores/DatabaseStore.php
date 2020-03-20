<?php

namespace Ersee\LaravelSetting\Stores;

use Ersee\LaravelSetting\Contracts\Store;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Str;

class DatabaseStore implements Store
{
    /**
     * 数据库连接实例.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * 表名称.
     *
     * @var string
     */
    protected $table;

    /**
     * 存储项目.
     *
     * @var array
     */
    protected $items;

    /**
     * 创建数据库存储实例.
     *
     * @param \Illuminate\Database\ConnectionInterface $connection
     * @param string                                   $table
     *
     * @return void
     */
    public function __construct(ConnectionInterface $connection, $table)
    {
        $this->table = $table;
        $this->connection = $connection;
    }

    /**
     * 获取全部项目.
     *
     * @return array
     */
    public function all()
    {
        return $this->items()->toArray();
    }

    /**
     * 获取项目.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->items()->get($key);
    }

    /**
     * 存储项目.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set($key, $value)
    {
        $value = $this->serialize($value);

        try {
            $this->items = null;

            return $this->table()->insert(compact('key', 'value'));
        } catch (\Exception $e) {
            $result = $this->table()->where('key', $key)->update(compact('value'));

            return $result > 0;
        }
    }

    /**
     * 删除项项目.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget($key)
    {
        $this->items = null;

        return $this->table()->where('key', '=', $key)->delete() > 0;
    }

    /**
     * 获取存储项目.
     *
     * @return array|\Illuminate\Support\Collection
     */
    protected function items()
    {
        if (null === $this->items) {
            $this->items = $this->table()->pluck('value', 'key')->map(function ($item) {
                return $this->unserialize($item);
            });
        }

        return $this->items;
    }

    /**
     * 获取查询构造器.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->connection->table($this->table);
    }

    /**
     * 序列化指定值.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function serialize($value)
    {
        $result = serialize($value);

        if ($this->connection instanceof PostgresConnection && Str::contains($result, "\0")) {
            $result = base64_encode($result);
        }

        return $result;
    }

    /**
     * 取消指定值序列化.
     *
     * @param string $value
     *
     * @return mixed
     */
    protected function unserialize($value)
    {
        if ($this->connection instanceof PostgresConnection && !Str::contains($value, [':', ';'])) {
            $value = base64_decode($value);
        }

        return unserialize($value);
    }
}
