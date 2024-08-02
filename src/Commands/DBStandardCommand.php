<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\DBFunctions;
use Vcian\LaravelDBAuditor\Traits\Rules;
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
            render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
        }

        render(view('DBAuditor::'.$connection.'.standard', ['tableStatus' => $tableStatus]));

        $continue = Constant::STATUS_TRUE;

        do {
            $tableName = $this->anticipate('Please enter table name if you want to see the table report', $this->getTableList());

            if (empty($tableName)) {
                return render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
            }

            $this->tableReport($tableName,$connection);
            $report = $this->confirm("Do you want see other table report?");

            if (!$report) {
                $continue = Constant::STATUS_FALSE;
            }
        } while ($continue === Constant::STATUS_TRUE);

        return self::SUCCESS;
    }

    /**
     * Display table report.
     * @param string $tableName
     * @param string $connection
     * @return void|null
     */
    public function tableReport(string $tableName, string $connection)
    {
        $tableStatus = $this->tableRules($tableName);

        if (!$tableStatus) {
            return render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
        }

        render(view('DBAuditor::'.$connection.'.table_standard', ['tableStatus' => $tableStatus]));
    }
}
