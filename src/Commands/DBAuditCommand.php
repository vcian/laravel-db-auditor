<?php

namespace Vcian\LaravelDBPlayground\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Constraint\Constraint;
use Vcian\LaravelDBPlayground\Constants\Constant;
use Vcian\LaravelDBPlayground\Services\AuditService;

use function Termwind\{render};

class DBAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:audit {table?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Audit : Check the Constarain';

    /**
     * Execute the console command.
     */
    public function handle(AuditService $auditService)
    {
        try {

            $tableName = $this->argument('table');

            $results = Constant::ARRAY_DECLARATION;
            $constrains = [
                Constant::CONSTRAIN_PRIMARY_KEY,
                Constant::CONSTRAIN_INDEX_KEY,
                Constant::CONSTRAIN_UNIQUE_KEY,
                Constant::CONSTRAIN_FOREIGN_KEY,
                Constant::CONSTRAIN_ALL_KEY
            ];

            $userInput = $this->choice(
                'Please Select',
                $constrains
            );

            $header = [
                Constant::HEADER_TITLE_TABLE_NAME,
                Constant::HEADER_TITLE_COLUMN_NAME,
                $userInput . ' ' . Constant::HEADER_TITLE_CONSTRAIN
            ];

            $auditService->setHeaders($header);

            if ($tableName) {
                $results = $auditService->checkConstrain($tableName, $userInput);
            } else {
                $results = $auditService->getList($userInput);
            }

            if (!$results) {
                return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">No Table Found</div>');
            }

            $this->table($auditService->getHeaders(), $results);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">' . $exception->getMessage() . '</div>');
        }

        return self::SUCCESS;
    }
}
