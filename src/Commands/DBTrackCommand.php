<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Constants\Constant;
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
        $actionKeywords = ['U' => Constant::UPDATE, 'u' => Constant::UPDATE, 'c' => Constant::CREATE, 'C' => Constant::CREATE];
        $statusKeywords = ['M' => Constant::MIGRATED, 'm' => Constant::MIGRATED, 'P' => Constant::PENDING, 'p' => Constant::PENDING];

        $data = $this->collectDataFromFile();

        if ($this->option(Constant::TABLE)) {

            $data = $this->filter(Constant::TABLE, $data, $this->option(Constant::TABLE));

        } elseif ($this->option(Constant::ACTION)) {

            if (array_key_exists($this->option(Constant::ACTION), $actionKeywords)) {
                $action = $actionKeywords[$this->option(Constant::ACTION)];
            } else {
                $action = ucfirst($this->option(Constant::ACTION));
            }
            $data = $this->filter(Constant::ACTION, $data, $action);

        } elseif ($this->option(Constant::STATUS)) {

            if (array_key_exists($this->option(Constant::STATUS), $statusKeywords)) {
                $status = $statusKeywords[$this->option(Constant::STATUS)];
            } else {
                $status = ucfirst($this->option(Constant::STATUS));
            }
            $data = $this->filter(Constant::STATUS, $data, $status);
        }

        table(
            ['Date', 'File Name', 'Table', 'Fields', 'Action', 'Status', 'Created By'],
            $data
        );

        return self::SUCCESS;
    }
}
