<?php

namespace Vcian\LaravelDBPlayground\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBPlayground\Constants\Constant;
use Vcian\LaravelDBPlayground\Services\AuditService;

use function Termwind\{render};

class DBPlayground extends Command
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

            $referenceTable = null;
            $referenceField = null;

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

            if ($tableName) {
                $results = $auditService->checkConstrain($tableName, $userInput);
                if (!$results) {
                    return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">No Table Found</div>');
                }
                render(view('DBPlayground::playground', ['tables' => $results]));

                $checkUserHasFields = $auditService->getFieldByUserInput($tableName, $userInput);
                if ($checkUserHasFields) {
                    $userSelection = $this->choice('Do you want to add constrain into your table?', ['Yes', 'No']);
                    if ($userSelection == "Yes") {
                        if ($userInput === Constant::CONSTRAIN_ALL_KEY) {
                            $options = [Constant::CONSTRAIN_INDEX_KEY, Constant::CONSTRAIN_UNIQUE_KEY, Constant::CONSTRAIN_FOREIGN_KEY];
                            if (!$auditService->checkTableHasPrimaryKey($tableName)) {
                                array_push($options, Constant::CONSTRAIN_PRIMARY_KEY);
                            }
                            $userInput = $this->choice("Please select constrain which you want process", $options);
                        }
                        $selectField = $this->choice("Please Select Field where you want to apply " . strtolower($userInput) . " key", $checkUserHasFields);
                        if ($userInput === Constant::CONSTRAIN_FOREIGN_KEY) {
                            $referenceTable = $this->ask("Please add refrence table name");
                            $referenceField = $this->ask("Please add refrence table primary key name");
                        }

                        $auditService->addConstrain($tableName, $selectField, $userInput, $referenceTable, $referenceField);
                        render('<div class="w-100 px-1 p-1 bg-green-600 text-center">Run Successfully</div>');
                    }
                }
            } else {
                $results = $auditService->getList($userInput);
                if (!$results) {
                    return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">No Table Found</div>');
                }
                render(view('DBPlayground::playground', ['tables' => $results]));
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return render('<div class="w-100 px-1 p-1 bg-red-600 text-center">' . $exception->getMessage() . '</div>');
        }

        return self::SUCCESS;
    }
}
