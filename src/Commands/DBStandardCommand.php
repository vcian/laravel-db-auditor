<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
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
            return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">No Table Found</div>');
        }
        render( view('DBAuditor::standard', [ 'tableStatus' => $tableStatus ]) );
        return self::SUCCESS;
    }
}
