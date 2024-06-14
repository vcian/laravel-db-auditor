<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Rules;
use function Termwind\{render};

class DBStandardCommand extends Command
{
    use Rules;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:standard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is use for check database table standards';

    /**
     * Execute the console command.
     */
    public function handle(): ?int
    {
        $tableStatus = $this->tablesRule();

        if (!$tableStatus) {
            render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
        }

        render(view('DBAuditor::standard', ['tableStatus' => $tableStatus]));

        $continue = Constant::STATUS_TRUE;

        do {
            $tableName = $this->anticipate('Please enter table name if you want to see the table report', $this->getTableList());

            if (empty($tableName)) {
                return render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
            }

            $tableStatus = $this->tableRules($tableName);

            if (!$tableStatus) {
                return render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
            } else {
                render(view('DBAuditor::fail_standard_table', ['tableStatus' => $tableStatus]));
            }

            $report = $this->confirm("Do you want see other table report?");

            if (!$report) {
                $continue = Constant::STATUS_FALSE;
            }
        } while ($continue === Constant::STATUS_TRUE);

        return self::SUCCESS;
    }
}
