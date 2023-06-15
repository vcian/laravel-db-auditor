<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DBAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Audit : Check Standard and Constraint';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        $commandSelect = $this->choice('Please Select feature which would you like to do', [Constant::STANDARD_COMMAND, Constant::CONSTRAINT_COMMAND, Constant::SUMMARY_COMMAND]);

        if ($commandSelect === Constant::STANDARD_COMMAND) {
            $this->call('db:standard');
        }

        if ($commandSelect === Constant::CONSTRAINT_COMMAND) {
            $this->call('db:constraint');
        }

        if ($commandSelect === Constant::SUMMARY_COMMAND) {
            $this->call('db:summary');
        }
    }
}
