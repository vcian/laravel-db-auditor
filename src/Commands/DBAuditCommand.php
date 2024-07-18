<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;

use function Laravel\Prompts\select;

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
    protected $description = 'Database Audit : Check Standard and Constraint with track';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
       $db = match (connection_driver()) {
            Constant::SQLITE_DB => config('db-auditor.sqlite_commands'),
            Constant::MYSQL_DB => config('db-auditor.mysql_commands'),
        };

        $commandSelect = select(
            label: 'Please Select feature which would you like to do',
            options: $db,
            default: Constant::SUMMARY_COMMAND
        );

        if ($commandSelect === Constant::STANDARD_COMMAND) {
            $this->call('db:standard');
        }

        if ($commandSelect === Constant::CONSTRAINT_COMMAND) {
            $this->call('db:constraint');
        }

        if ($commandSelect === Constant::SUMMARY_COMMAND) {
            $this->call('db:summary');
        }

        if ($commandSelect === Constant::TRACK_COMMAND) {
            $this->call('db:track');
        }
    }
}
