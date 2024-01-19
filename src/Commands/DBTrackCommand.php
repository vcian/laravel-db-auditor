<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Traits\DBMigrationTrack;

use function Laravel\Prompts\table;
use function Termwind\{render};

class DBTrackCommand extends Command
{
    use DBMigrationTrack;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:track {--table=} {--action=} {--status=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track the the database information.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $actionKeywords = ['U' => 'Update', 'u' => 'Update', 'c' => 'Create', 'C' => 'Create'];
        $statusKeywords = ['M' => 'Migrated', 'm' => 'Migrated', 'P' => 'Pending', 'p' => 'Pending'];

        $data = $this->collectDataFromFile('command');

        if ($this->option('table')) {

            $data = $this->filter('table', $data, $this->option('table'));

        } elseif ($this->option('action')) {

            if (array_key_exists($this->option('action'), $actionKeywords)) {
                $action = $actionKeywords[$this->option('action')];
            } else {
                $action = ucfirst($this->option('action'));
            }
            $data = $this->filter('action', $data, $action);

        } elseif ($this->option('status')) {

            if (array_key_exists($this->option('status'), $statusKeywords)) {
                $status = $statusKeywords[$this->option('status')];
            } else {
                $status = ucfirst($this->option('status'));
            }
            $data = $this->filter('status', $data, $status);
        }

        table(
            ['Date', 'Table', 'Fields', 'Action', 'File Name', 'Status', 'Created By'],
            $data
        );

        return self::SUCCESS;
    }
}
