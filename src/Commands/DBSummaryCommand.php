<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Traits\DBConnection;

use function Termwind\{render};

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
        $this->table(
            ['Database Name', 'Size', 'Table Count', 'Engin', 'Character Set'],
            [[
                $this->getDatabaseName(),
                $this->getDatabaseSize(),
                count($this->getTableList()),
                $this->getDatabaseEngin(),
                $this->getCharacterSetName(),
            ]]
        );

        return self::SUCCESS;
    }
}
