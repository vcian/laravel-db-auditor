<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Services\AuditService;

use function Termwind\{render};
use function Termwind\{renderUsing};

class DBConstraintCommand extends Command
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
    protected $description = 'Table Constraint Playground';

    /**
     * Execute the console command.
     * @param AuditService
     */
    public function handle(AuditService $auditService)
    {
        try {

            $tableName = $this->components->choice(
                __('Lang::messages.constraint.question.table_selection'),
                $auditService->getTablesList()
            );

            $this->displayTable($tableName);

            if ($tableName) {

                $flag = Constant::STATUS_TRUE;

                do {
                    $continue = Constant::STATUS_TRUE;
                    $noConstrainfields = $auditService->getNoConstraintFields($tableName);
                    $constrainList = $auditService->getConstraintList($tableName, $noConstrainfields);

                    if ($noConstrainfields) {

                        $userInput = $this->confirm(__('Lang::messages.constraint.question.continue'));

                        if ($userInput) {

                            $selectConstrain = $this->choice(
                                __('Lang::messages.constraint.question.constraint_selection'),
                                $constrainList
                            );

                            if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY || $selectConstrain === Constant::CONSTRAINT_UNIQUE_KEY) {
                                $tableHasValue = $auditService->tableHasValue($tableName);

                                if ($tableHasValue) {
                                    $continue = Constant::STATUS_FALSE;
                                    render('<div class="w-120 px-2 p-1 bg-red-600 text-center"> 😢 Can not apply ' . strtolower($selectConstrain) . ' key | Please trancate table 😎 </div>');
                                }
                            }

                            if ($continue) {

                                if ($selectConstrain === Constant::CONSTRAINT_PRIMARY_KEY || $selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                                    $fields = $noConstrainfields['integer'];
                                } else {
                                    $fields = $noConstrainfields['mix'];
                                }

                                $selectField = $this->choice(
                                    __('Lang::messages.constraint.question.field_selection').' '. strtolower($selectConstrain) . ' key',
                                    $fields
                                );

                                if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                                    $referenceTable = $this->ask(__('Lang::messages.constraint.question.foreign_table'));
                                    $referenceField = $this->ask(__('Lang::messages.constraint.question.foreign_field'));
                                    $auditService->addConstraint($tableName, $selectField, $selectConstrain, $referenceTable, $referenceField);
                                }

                                $auditService->addConstraint($tableName, $selectField, $selectConstrain);

                                renderUsing($this->output);

                                render('<div class="w-120 px-2 p-1 bg-green-600 text-center"> 😎 ' . __('Lang::messages.constraint.success_message.constraint_added') . ' 😎 </div>');

                                $this->displayTable($tableName);
                            }
                        } else {
                            $flag = Constant::STATUS_FALSE;
                        }
                    } else {
                        $flag = Constant::STATUS_FALSE;
                    }
                } while ($flag === Constant::STATUS_TRUE);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $exception->getMessage();
        }

        return self::SUCCESS;
    }

    /**
     * Display selected table
     * @param string $tableName
     * @return render
     */
    public function displayTable($tableName)
    {
        $auditService = resolve(AuditService::class);

        $data = [
            "table" => $tableName,
            "size" => $auditService->getTableSize($tableName),
            "fields" => $auditService->getTableFields($tableName),
            'field_count' => count($auditService->getTableFields($tableName)),
            'constrain' => [
                'primary' => $auditService->getConstraintField($tableName, Constant::CONSTRAINT_PRIMARY_KEY),
                'unique' => $auditService->getConstraintField($tableName, Constant::CONSTRAINT_UNIQUE_KEY),
                'foreign' => $auditService->getConstraintField($tableName, Constant::CONSTRAINT_FOREIGN_KEY),
                'index' => $auditService->getConstraintField($tableName, Constant::CONSTRAINT_INDEX_KEY)
            ]
        ];

        render(view('DBAuditor::constraint', ['data' => $data]));
    }
}
