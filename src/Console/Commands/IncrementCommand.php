<?php

namespace Ersee\LaravelSetting\Console\Commands;

use Ersee\LaravelSetting\Facades\Setting;

class IncrementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:increment {key : Setting key} {value=1 : Increment value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment the value of an item in the setting.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Setting::increment($this->argument('key'), (int) $this->argument('value'));

        $this->showTable(Setting::get([$this->argument('key')]));
    }
}
