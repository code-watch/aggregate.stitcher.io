<?php

namespace App\Console\Commands;

use App\Jobs\SyncSourceJob;
use App\Models\Source;
use Illuminate\Console\Command;
use Throwable;

class SyncCommand extends Command
{
    protected $signature = 'sync {--source=}';

    public function handle()
    {
        $filter = $this->option('source');

        if ($filter) {
            $sources = Source::query()
                ->where(
                    is_int($filter) ? 'id' : 'name',
                    $filter
                )
                ->get();
        } else {
            $sources = Source::all();
        }

        foreach ($sources as $source) {
            $this->comment("Syncing source <fg=green>{$source->name}</> (#{$source->id})");

            try {
                dispatch(new SyncSourceJob($source));
            } catch (Throwable $e) {
                $this->output->writeln("\t[<fg=green>{$source->name}</> (#{$source->id})] <bg=red;fg=white>{$e->getMessage()}</>");
            }
        }
    }
}
