<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Traits\DBConnection;
use function Laravel\Prompts\table;

class DBSummaryCommand extends Command
{
    use DBConnection;

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
        table(
            headers: ['Database Name', 'Size', 'Table Count', 'Engin', 'Character Set', 'Last Modified Table'],
            rows: [[
                        $this->getDatabaseName(),
                        $this->getDatabaseSize(),
                        count($this->getTableList()),
                        $this->getDatabaseEngin(),
                        $this->getCharacterSetName(),
                        $this->getLastModifiedTable()
                    ]]
            );

        return self::SUCCESS;
    }
}
