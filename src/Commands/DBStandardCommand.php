<?php

namespace Vcian\LaravelDBPlayground\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBPlayground\Services\RuleService;
use function Termwind\render;

class DBStandardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:standard {table?}';

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
        $tableName = $this->argument('table');

        if($tableName) {
            $tableResult = $ruleService->tableRules($tableName);
        } else {
            $tableResult = $ruleService->tablesRule();
        }

        if (!$tableResult) {
            return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">No Table Found</div>');
        }

        return render(
            view('DBAuditor::standard', [
                'tables' => $tableResult,
            ])
        );
    }
}
