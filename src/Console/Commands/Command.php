<?php

namespace Ersee\LaravelSetting\Console\Commands;

use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * 展示表格
     *
     * @param array $items
     */
    public function showTable($items)
    {
        $this->table(
            [
                'key',
                'value',
            ],
            collect($items)
                ->map(function ($item, $key) {
                    return ['key' => $key, 'value' => $item];
                })
        );
    }
}
