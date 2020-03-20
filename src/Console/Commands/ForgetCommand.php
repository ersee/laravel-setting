<?php

namespace Ersee\LaravelSetting\Console\Commands;

use Ersee\LaravelSetting\Facades\Setting;

class ForgetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:forget {key* : Setting key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forget one or more settings.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (Setting::forget($this->argument('key'))) {
            $this->info('Forget one or more settings successfully.');
        } else {
            $this->warn('No setting matches the given key.');
        }
    }
}
