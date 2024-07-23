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
       $commands = match (connection_driver()) {
            Constant::SQLITE_DB => config('db-auditor.sqlite_commands'),
            Constant::MYSQL_DB => config('db-auditor.mysql_commands'),
            Constant::POSTGRESQL_DB => config('db-auditor.postgresql_commands'),
        };

        $commandSelect = select(
            label: 'Please Select feature which would you like to do',
            options: $commands,
            default: Constant::SUMMARY_COMMAND
        );

        match ($commandSelect) {
            Constant::STANDARD_COMMAND => $this->call('db:standard'),
            Constant::CONSTRAINT_COMMAND => $this->call('db:constraint'),
            Constant::TRACK_COMMAND => $this->call('db:track'),
            default => $this->call('db:summary'),
        };
    }
}
