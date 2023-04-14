<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Services\AuditService;

use function Termwind\{render};
use function Termwind\{renderUsing};

class DBConstrainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:constraint {table?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Table Constrain Playground';

    /**
     * Execute the console command.
     */
    public function handle(AuditService $auditService)
    {
        try {

            $tableName = $this->components->choice(
                'Which table whould you like to audit?',
                $auditService->getTablesList()
            );

            $data = [
                "table" => $tableName,
                "size" => $auditService->getTableSize($tableName),
                "fields" => $auditService->getTableFields($tableName),
                'field_count' => count($auditService->getTableFields($tableName)),
                'constrain' => [
                    'primary' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_PRIMARY_KEY),
                    'unique' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_UNIQUE_KEY),
                    'foreign' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_FOREIGN_KEY),
                    'index' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_INDEX_KEY)
                ]
            ];

            render(view('DBAuditor::constraint', ['data' => $data]));

            if ($tableName) {

                $continue = Constant::STATUS_TRUE;
                do {

                    $noConstrainfields = $auditService->getNoConstrainFields($tableName);
                    $constrainList = $auditService->getConstrainList($tableName, $noConstrainfields);

                    if ($noConstrainfields) {

                        $userInput = $this->confirm("Do you want add more constrain?");

                        if ($userInput) {

                            $selectConstrain = $this->choice(
                                'Please select constrain which you want to add',
                                $constrainList
                            );

                            if ($selectConstrain === Constant::CONSTRAIN_PRIMARY_KEY || $selectConstrain === Constant::CONSTRAIN_FOREIGN_KEY) {
                                $fields = $noConstrainfields['integer'];
                            } else {
                                $fields = $noConstrainfields['mix'];
                            }

                            $selectField = $this->choice(
                                'Please select field where you want to add ' . $selectConstrain,
                                $fields
                            );

                            if ($selectConstrain === Constant::CONSTRAIN_FOREIGN_KEY) {
                                $tableHasValue = $auditService->tableHasValue($tableName);

                                if ($tableHasValue) {
                                    render('<div class="w-120 px-2 p-1 bg-red-600 text-center"> 😢 Can not apply Foreign Key | Please trancate table 😎 </div>');
                                } else {
                                    $referenceTable = $this->ask("Please add foreign table name");
                                    $referenceField = $this->ask("Please add foreign table primary key name");
                                    $auditService->addConstrain($tableName, $selectField, $selectConstrain, $referenceTable, $referenceField);
                                }
                            }

                            $auditService->addConstrain($tableName, $selectField, $selectConstrain);

                            renderUsing($this->output);

                            render('<div class="w-120 px-2 p-1 bg-green-600 text-center"> 😎 Constrain Add Successfully 😎 </div>');

                            $data = [
                                "table" => $tableName,
                                "size" => $auditService->getTableSize($tableName),
                                "fields" => $auditService->getTableFields($tableName),
                                'field_count' => count($auditService->getTableFields($tableName)),
                                'constrain' => [
                                    'primary' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_PRIMARY_KEY),
                                    'unique' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_UNIQUE_KEY),
                                    'foreign' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_FOREIGN_KEY),
                                    'index' => $auditService->getConstrainField($tableName, Constant::CONSTRAIN_INDEX_KEY)
                                ]
                            ];

                            render(view('DBAuditor::constraint', ['data' => $data]));
                        } else {
                            $continue = Constant::STATUS_FALSE;
                        }
                    } else {
                        $continue = Constant::STATUS_FALSE;
                    }
                } while ($continue === Constant::STATUS_TRUE);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $exception->getMessage();
        }

        return self::SUCCESS;
    }
}
