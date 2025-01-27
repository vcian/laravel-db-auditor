<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;

use function Laravel\Prompts\search;
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
        $commandSelect = select(
            label: 'Please Select feature which would you like to do',
            options: match (connection_driver()) {
                Constant::SQLITE_DB => config('audit.sqlite_commands'),
                Constant::MYSQL_DB => config('audit.mysql_commands'),
                Constant::POSTGRESQL_DB => config('audit.pgsql_commands'),
            },
            default: Constant::SUMMARY_COMMAND
        );

        match ($commandSelect) {
            Constant::STANDARD_COMMAND => $this->call('db:standard'),
            Constant::CONSTRAINT_COMMAND => $this->call('db:constraint'),
            Constant::TRACK_COMMAND => $this->call('db:track'),
            Constant::CHECK_PERFORMANCE_PARAMETER_COMMAND => $this->call('db:check-performance-parameter'),
            default => $this->call('db:summary'),
        };
    }
}
