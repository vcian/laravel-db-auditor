<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Services\RuleService;
use function Termwind\render;

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
    public function handle(RuleService $ruleService)
    {
        $tableStatus = $ruleService->tablesRule();

        if (!$tableStatus) {
            return render('<div class="w-100 px-1 p-1 bg-red-600 text-center"> 😢 No Table Found 😩 </div>');
        }

        render(view('DBAuditor::standard', ['tableStatus' => $tableStatus]));

        $continue = Constant::STATUS_TRUE;

        do {

            $tableName = $this->ask('Please select table if you want to see the report');

            $tableStatus = $ruleService->tableRules($tableName);

            if (!$tableStatus) {
                render('<div class="w-120 py-2 px-2 bg-red-600 pr-0 pl-0 text-center">😢 No Table Found 😩</div>');
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
