<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;

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
            default => $this->mysql(),
        };
    }

    /**
     * @return int
     */
    public function sqlite(): int
    {
        $this->table(
            ['Database Name', 'Size', 'Table Count'],
            [[
                database_name(),
                $this->getDatabaseSize(),
                count($this->getTableList())
            ]]
        );

        return self::SUCCESS;
    }

    /**
     * @return int
     */
    public function mysql(): int
    {
        $this->table(
            ['Database Name', 'Size', 'Table Count', 'Engin', 'Character Set'],
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
}
