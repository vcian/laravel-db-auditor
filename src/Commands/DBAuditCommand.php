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
    protected $description = 'Database Audit : Check Standard and Constrain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $commandSelect = $this->choice(
            'Please Select',
            [Constant::STANDARD_COMMAND, Constant::CONSTRAIN_COMMAND]
        );

        if ($commandSelect === Constant::STANDARD_COMMAND) {
            $this->call('db:standard');
        }

        if ($commandSelect === Constant::CONSTRAIN_COMMAND) {
            $this->call('db:constrain');
        }
    }
}
