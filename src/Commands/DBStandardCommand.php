<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Services\RuleService;
use function Termwind\{render};

class DBStandardCommand extends Command
{
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): ?int
    {
        $ruleService = app(RuleService::class);
        $tableStatus = $ruleService->tablesRule();

        if (!$tableStatus) {
            render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
        }

        render(view('DBAuditor::standard', ['tableStatus' => $tableStatus]));

        $continue = Constant::STATUS_TRUE;

        do {
            $tableName = $this->ask('Please enter table name if you want to see the table report');

            if (empty($tableName)) {
                return render(view('DBAuditor::error_message', ['message' => 'No Table Found']));
            }

            $tableStatus = $ruleService->tableRules($tableName);

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
