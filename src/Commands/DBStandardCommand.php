<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\DBFunctions;
use Vcian\LaravelDBAuditor\Traits\Rules;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\confirm;
use function Termwind\{render};

class DBStandardCommand extends Command
{
    use Rules, DBFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:standard {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is use for check database table standards';

    protected string $connection;

    /**
     * Execute the console command.
     */
    public function handle(): ?int
    {
        $this->connection = connection_driver();

        if ($this->option('table')){
            return $this->tableReport($this->option('table'), $this->connection);
        }

        return $this->allTable($this->connection);
    }

    /**
     * Display all table details.
     * @param string $connection
     * @return int|null
     */
    public function allTable(string $connection): ?int
    {

        $tableStatus = $this->allTablesRules();

        if (!$tableStatus) {
            return $this->components->error('No Table Found');
        }

        render(view('DBAuditor::'.$connection.'.standard', ['tableStatus' => $tableStatus]));

        $continue = Constant::STATUS_TRUE;

        do {
            $tableName = suggest(
                label: 'Please select table name if you want to see the table report',
                options: $this->getTableList(),
                placeholder: 'E.g. Users',
            );

            if (empty($tableName)) {
                $this->components->error('No Table Found');
                $continue = confirm("Do you want to try again?");
                if (!$continue) {
                    return self::SUCCESS;
                }
                continue;
            }

            $tableStatus = $this->tableRules($tableName);

            if (!$tableStatus) {
                $this->components->error('No Table Found');
                $continue = confirm("Do you want to try again?");
                if (!$continue) {
                    return self::SUCCESS;
                }
                continue;
            } else {
                render(view('DBAuditor::'.$connection.'.table_standard', ['tableStatus' => $tableStatus]));
            }
            $report = confirm("Do you want see other table report?");

            if (!$report) {
                $continue = Constant::STATUS_FALSE;
            }
        } while ($continue === Constant::STATUS_TRUE);

        return self::SUCCESS;
    }
}
