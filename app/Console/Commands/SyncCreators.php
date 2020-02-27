<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SyncCreators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'creators:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步創作者身份';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->reset();

        foreach (config('bookwalker.creators') as $type) {
            // using join is faster faster faster than where in
            // https://stackoverflow.com/a/1262848

            $table = sprintf('book_%s', $type);

            $field = sprintf('%s.creator_id', $table);

            DB::table('creators')
                ->join($table, 'creators.id', '=', $field)
                ->update([$type => true]);

            $this->info(
                sprintf('%s sync successfully.', Str::studly($type))
            );
        }
    }

    /**
     * Reset creators statistic.
     *
     * @return int
     */
    protected function reset(): int
    {
        return DB::table('creators')->update(
            array_fill_keys(config('bookwalker.creators'), 0)
        );
    }
}
