<?php

namespace Ersee\LaravelSetting\Console\Commands;

use Ersee\LaravelSetting\Facades\Setting;

class SetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:set {key : Setting key} {value : Setting value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new setting.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Setting::set($this->argument('key'), $this->argument('value'));

        $this->showTable(Setting::get([$this->argument('key')]));
    }
}
