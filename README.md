#Laravel Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ersee/laravel-setting.svg?style=flat-square)](https://packagist.org/packages/ersee/laravel-setting)
[![Total Downloads](https://img.shields.io/packagist/dt/ersee/laravel-setting.svg?style=flat-square)](https://packagist.org/packages/ersee/laravel-setting)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Global setting package for Laravel application.

## Installation
```bash
composer require ersee/laravel-setting -vvv
```
Publish config:
```bash
php artisan vendor:publish --provider="Ersee\LaravelSetting\Providers\SettingServiceProvider"
```
Run migrations:
```bash
php artisan migrate
```

## Usage

```bash
// get all
\Setting::all(); // array
setting()->all();

// check exists
\Setting::has('key'); // bool
setting()->has('key');

// get
\Setting::get('key'); // default null
setting('key');
\Setting::get('key', 'default');
setting('key', 'default');
\Setting::get(['key1', 'key2']); // default null
setting()->get(['key1', 'key2']);
\Setting::get(['key1' => 'default1', 'key2' => 'default2']);
setting()->get(['key1' => 'default1', 'key2' => 'default2']);

// set
\Setting::set('key', 'value');
setting(['key' => 'value']);
\Setting::set(['key1' => 'value1', 'key2' => 'value2']);
setting(['key1' => 'value1', 'key2' => 'value2']);

// increment or decrement
\Setting::increment('key', 1);
setting()->increment('key', 1);
\Setting::decrement('key', 1);
setting()->decrement('key', 1);

// forget
\Setting::forget('key');
setting()->forget('key');
\Setting::forget(['key1', 'key2']);
setting()->forget(['key1', 'key2']);
```

### Console commands

```bash
php artisan setting:all
php artisan setting:get <key>...
php artisan setting:set <key> <value>
php artisan setting:increment <key> [<value=1>]
php artisan setting:decrement <key> [<value=1>]
php artisan setting:forget <key>...
```

### Events

- `\Ersee\LaravelSetting\Events\Missed::class`
- `\Ersee\LaravelSetting\Events\Hit::class`
- `\Ersee\LaravelSetting\Events\Written::class`
- `\Ersee\LaravelSetting\Events\Forgotten::class`

## License

MIT
