<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Traits\DBFunctions;
use function Termwind\{render};

class DBSummaryCommand extends Command
{
    use DBFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return match (connection_driver()) {
            'sqlite' => $this->sqlite(),
            'pgsql' => $this->pgsql(),
            default => $this->mysql(),
        };
    }

    /**
     * Sqlite
     *
     * @return int
     */
    public function sqlite(): int
    {
        $this->table(
            [
                'Database Version',
                'Database Name',
                'Table Count',
                'Busy Timeout',
                'Default Cache Size'
            ],
            [[
                collect(DB::select('PRAGMA data_version;'))->first()->data_version,
                database_name(),
                count($this->getTableList()),
                collect(DB::select('PRAGMA busy_timeout;')[0])['timeout'],
                get_sqlite_database_cache_size(),
            ]]
        );

        return self::SUCCESS;
    }

    /**
     * Mysql
     *
     * @return int
     */
    public function mysql(): int
    {
        $this->table(
            ['Database Name', 'Size', 'Table Count', 'DB Version', 'Character Set'],
            [[
                database_name(),
                $this->getDatabaseSize(),
                count($this->getTableList()),
                $this->getDatabaseEngin(),
                $this->getCharacterSetName(),
            ]]
        );

        return self::SUCCESS;
    }

    public function pgsql(): int
    {
        $this->table(
            ['Database Name', 'Size', 'Table Count', 'Character Set'],
            [[
                database_name(),
                $this->getDatabaseSize(),
                $this->getTableList(),
                $this->getCharacterSetName(),
            ]]
        );

        return self::SUCCESS;
    }
}
