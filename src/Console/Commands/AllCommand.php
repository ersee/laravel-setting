<?php

namespace Ersee\LaravelSetting\Console\Commands;

use Ersee\LaravelSetting\Facades\Setting;

class AllCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'setting:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all settings.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->showTable(Setting::all());
    }
}
