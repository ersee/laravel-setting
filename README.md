# Laravel Setting

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

- ##### Facade

```bash
// get all
\Setting::all(); // array

// check exists
\Setting::has('key'); // bool

// get
\Setting::get('key'); // default null
\Setting::get('key', 'default'); 
\Setting::get(['key1', 'key2']); // default null
\Setting::get(['key1' => 'default1', 'key2' => 'default2']);

// set
\Setting::set('key', 'value');
\Setting::set(['key1' => 'value1', 'key2' => 'value2']);

// set datatype
\Setting::set('key', 100); // int
\Setting::set('key', 100.123); // float
\Setting::set('key', 'string'); // string
\Setting::set('key', true); // bool
\Setting::set('key', ['ka' => 'va', 'kb' => 'vb']); // array
\Setting::set('key', new \DateTime()); // object

// increment or decrement
\Setting::increment('key');
\Setting::increment('key', 100);
\Setting::decrement('key');
\Setting::decrement('key', 100);

// forget
\Setting::forget('key');
\Setting::forget(['key1', 'key2']);
```

- ##### Helper

```bash
// get all
setting()->all(); // array

// check exists
setting()->has('key'); // bool

// get
setting('key'); // default null
setting('key', 'default'); 
setting()->get(['key1', 'key2']); // default null
setting()->get(['key1' => 'default1', 'key2' => 'default2']);

// set
setting(['key1' => 'value1', 'key2' => 'value2']);

// set datatype
setting(['key' => 100]); // int
setting(['key' => 100.123]); // float
setting(['key' => 'string']); // string
setting(['key' => true]); // bool
setting(['key' => ['ka' => 'va', 'kb' => 'vb']]); // array
setting(['key' => new \DateTime()]); // object

// increment or decrement
setting()->increment('key');
setting()->increment('key', 100);
setting()->decrement('key');
setting()->decrement('key', 100);

// forget
setting()->forget('key');
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
