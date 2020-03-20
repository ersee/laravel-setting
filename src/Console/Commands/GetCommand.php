<?php

namespace Ersee\LaravelSetting\Console\Commands;

use Ersee\LaravelSetting\Facades\Setting;

class GetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:get {key* : Setting key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get one or more settings.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->showTable(Setting::get($this->argument('key')));
    }
}
