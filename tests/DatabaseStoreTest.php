<?php

namespace Ersee\LaravelSetting\Tests;

use Ersee\LaravelSetting\SettingManager;
use Illuminate\Support\Facades\Event;

class DatabaseStoreTest extends TestCase
{
    /**
     * 设置环境变量.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * 设置测试环境.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->artisan('migrate:fresh');
    }

    public function testInstance()
    {
        $this->assertInstanceOf(SettingManager::class, app('Ersee\LaravelSetting\SettingManager'));
    }

    public function testFacade()
    {
        \Setting::set('ka', 'va');
        \Setting::set(['kb' => 'vb', 'kc' => 'vc']);

        $this->assertSame('va', \Setting::get('ka'));
        $this->assertSame('vb', \Setting::get('kb'));
        $this->assertSame('vc', \Setting::get('kc'));

        // 默认值
        $this->assertSame('vad', \Setting::get('kad', 'vad'));
        \Setting::set('kad', 'vad');
        $this->assertSame('vad', \Setting::get('kad', 'kkk'));

        // 存在/删除
        $this->assertTrue(\Setting::has('ka'));
        $this->assertTrue(\Setting::forget('ka'));
        $this->assertFalse(\Setting::has('ka'));
        $this->assertFalse(\Setting::forget('ka'));

        // 自增/自减
        \Setting::set('kid', 100);
        \Setting::increment('kid');
        $this->assertSame(101, \Setting::get('kid'));
        \Setting::increment('kid', 10);
        $this->assertSame(111, \Setting::get('kid'));
        \Setting::decrement('kid');
        $this->assertSame(110, \Setting::get('kid'));
        \Setting::decrement('kid', 10);
        $this->assertSame(100, \Setting::get('kid'));

        // 判断字符串
        \Setting::set('kts', 'vts');
        $this->assertIsString(\Setting::get('kts'));

        // 判断整数
        \Setting::set('kti', 100);
        $this->assertIsInt(\Setting::get('kti'));

        // 判断浮点
        \Setting::set('ktd', 100.99);
        $this->assertIsFloat(\Setting::get('ktd'));

        // 判断布尔
        \Setting::set('ktb', true);
        $this->assertIsBool(\Setting::get('ktb'));

        // 判断数组
        \Setting::set('kta', ['ka' => 'va', 'kb' => 'vb']);
        $this->assertIsArray(\Setting::get('kta'));

        // 判断对象
        \Setting::set('kto', new \DateTime());
        $this->assertInstanceOf(\DateTime::class, \Setting::get('kto'));
    }

    public function testHelper()
    {
        setting(['ka' => 'va', 'kb' => 'vb', 'kc' => 'vc']);
        $this->assertSame('va', setting('ka'));
        $this->assertSame('vb', setting('kb'));
        $this->assertSame('vc', setting('kc'));

        // 默认值
        $this->assertSame('vad', setting('kad', 'vad'));
        setting(['kad' => 'vad']);
        $this->assertSame('vad', setting('kad', 'kkk'));

        // 存在/删除
        $this->assertTrue(setting()->has('ka'));
        $this->assertTrue(setting()->forget('ka'));
        $this->assertFalse(setting()->has('ka'));
        $this->assertFalse(setting()->forget('ka'));

        // 自增/自减
        setting(['kid' => 100]);
        setting()->increment('kid');
        $this->assertSame(101, setting('kid'));
        setting()->increment('kid', 10);
        $this->assertSame(111, setting('kid'));
        setting()->decrement('kid');
        $this->assertSame(110, setting('kid'));
        setting()->decrement('kid', 10);
        $this->assertSame(100, setting('kid'));

        // 判断字符串
        setting(['kts' => 'vts']);
        $this->assertIsString(setting('kts'));

        // 判断整数
        setting(['kti' => 100]);
        $this->assertIsInt(setting('kti'));

        // 判断浮点
        setting(['ktd' => 100.99]);
        $this->assertIsFloat(setting('ktd'));

        // 判断布尔
        setting(['ktb' => true]);
        $this->assertIsBool(setting('ktb'));

        // 判断数组
        setting(['kta' => ['ka' => 'va', 'kb' => 'vb']]);
        $this->assertIsArray(setting('kta'));

        // 判断对象
        setting(['kto' => new \DateTime()]);
        $this->assertInstanceOf(\DateTime::class, setting('kto'));
    }

    public function testEvent()
    {
        Event::fake();

        \Setting::set('ak', 'av');
        Event::assertDispatched(\Ersee\LaravelSetting\Events\Written::class);

        \Setting::get('ak');
        Event::assertDispatched(\Ersee\LaravelSetting\Events\Hit::class);

        \Setting::get('zk');
        Event::assertDispatched(\Ersee\LaravelSetting\Events\Missed::class);

        \Setting::forget('ak');
        Event::assertDispatched(\Ersee\LaravelSetting\Events\Forgotten::class);
    }
}
