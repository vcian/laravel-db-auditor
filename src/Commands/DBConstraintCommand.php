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
     * @var bool
     */
    protected bool $skip = Constant::STATUS_FALSE;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:constraint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Table Constraint Playground';

    /**
     * Execute the console command.
     */
    public function handle(): int|string
    {
        try {
            $auditService = app(AuditService::class);

            $tableName = $this->components->choice(
                __('Lang::messages.constraint.question.table_selection'),
                $auditService->getTablesList()
            );

            $this->displayTable($tableName);

            if ($tableName) {

                $continue = Constant::STATUS_TRUE;

                do {
                    $noConstraintFields = $auditService->getNoConstraintFields($tableName);

                    if (empty($noConstraintFields)) {
                        $continue = Constant::STATUS_FALSE;
                    } else {
                        if ($this->confirm(__('Lang::messages.constraint.question.continue'))) {

                            $this->skip = Constant::STATUS_FALSE;
                            $constraintList = $auditService->getConstraintList($tableName, $noConstraintFields);
                            $selectConstrain = $this->choice(
                                __('Lang::messages.constraint.question.constraint_selection'),
                                $constraintList
                            );

                            $this->selectedConstraint($selectConstrain, $noConstraintFields, $tableName);
                        } else {
                            $continue = Constant::STATUS_FALSE;
                        }
                    }

                } while ($continue === Constant::STATUS_TRUE);
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
     * @return void
     */
    public function displayTable(string $tableName): void
    {
        $auditService = app(AuditService::class);

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

    /**
     * Display error messages
     * @param string $message
     */
    public function errorMessage(string $message): void
    {
        $this->skip = Constant::STATUS_TRUE;
        render(view('DBAuditor::error_message', ['message' => $message]));
    }

    /**
     * Display success messages
     * @param string $message
     */
    public function successMessage(string $message): void
    {
        render(view('DBAuditor::success_message', ['message' => $message]));
    }

    /**
     * Get Foreign Key Constrain
     * @param string $tableName
     * @param string $selectField
     * @return void
     */
    public function foreignKeyConstraint(string $tableName, string $selectField): void
    {
        $auditService = app(AuditService::class);
        $foreignContinue = Constant::STATUS_FALSE;
        $referenceField = Constant::NULL;
        $fields = Constant::ARRAY_DECLARATION;

        do {
            $referenceTable = $this->anticipate(__('Lang::messages.constraint.question.foreign_table'), $auditService->getTablesList());

            if ($referenceTable && $auditService->checkTableExistOrNot($referenceTable)) {

                foreach ($auditService->getTableFields($referenceTable) as $field) {
                    $fields[] = $field->COLUMN_NAME;
                }
                do {
                    $referenceField = $this->anticipate(__('Lang::messages.constraint.question.foreign_field'), $fields);

                    if (!$referenceField || !$auditService->checkFieldExistOrNot($referenceTable, $referenceField)) {
                        $this->errorMessage(__('Lang::messages.constraint.error_message.field_not_found'));
                    } else {
                        $foreignContinue = Constant::STATUS_TRUE;
                    }
                } while ($foreignContinue === Constant::STATUS_FALSE);

            } else {
                $this->errorMessage(__('Lang::messages.constraint.error_message.table_not_found'));
            }
        } while ($foreignContinue === Constant::STATUS_FALSE);

        $referenceFieldType = $auditService->getFieldDataType($referenceTable, $referenceField);
        $selectedFieldType = $auditService->getFieldDataType($tableName, $selectField);

        if ($referenceTable === $tableName) {
            $this->errorMessage(__('Lang::messages.constraint.error_message.foreign_selected_table_match', ['foreign' => $referenceTable, 'selected' => $tableName]));
        }

        if ($referenceFieldType['data_type'] !== $selectedFieldType['data_type']) {

            render('
            <div class="mt-1">
                <div class="flex space-x-1">
                    <span class="font-bold text-green">' . $selectedFieldType['data_type'] . '</span>
                    <i class="text-blue">' . $selectField . '</i>
                    <span class="flex-1 content-repeat-[.] text-gray"></span>
                    <i class="text-blue">' . $referenceField . '</i>
                    <span class="font-bold text-green">' . $referenceFieldType['data_type'] . '</span>
                </div>
            </div>
            ');
            $this->errorMessage(__('Lang::messages.constraint.error_message.foreign_not_apply'));
        } else {
            $auditService->addConstraint($tableName, $selectField, Constant::CONSTRAINT_FOREIGN_KEY, $referenceTable, $referenceField);
        }
    }

    /**
     * @param string $selectConstrain
     * @param array $noConstraintFields
     * @param string $tableName
     * @return void
     */
    public function selectedConstraint(string $selectConstrain, array $noConstraintFields, string $tableName): void
    {

        $auditService = app(AuditService::class);

        if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
            $tableHasValue = $auditService->tableHasValue($tableName);

            if ($tableHasValue) {
                $this->errorMessage(__('Lang::messages.constraint.error_message.constraint_not_apply', ['constraint' => strtolower($selectConstrain)]));
            }
        }

        if (!$this->skip) {
            if ($selectConstrain === Constant::CONSTRAINT_PRIMARY_KEY || $selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                $fields = $noConstraintFields['integer'];
            } else {
                $fields = $noConstraintFields['mix'];
            }

            if ($selectConstrain === Constant::CONSTRAINT_UNIQUE_KEY) {
                $fields = $auditService->getUniqueFields($tableName, $noConstraintFields['mix']);
                if (empty($fields)) {
                    $this->errorMessage(__('Lang::messages.constraint.error_message.unique_constraint_not_apply'));
                }
            }

            if (!$this->skip) {
                $selectField = $this->choice(
                    __('Lang::messages.constraint.question.field_selection') . ' ' . strtolower($selectConstrain) . ' key',
                    $fields
                );

                if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                    $this->foreignKeyConstraint($tableName, $selectField);
                } else {
                    $auditService->addConstraint($tableName, $selectField, $selectConstrain);
                }
            }
        }

        if (!$this->skip) {
            renderUsing($this->output);

            $this->successMessage(__('Lang::messages.constraint.success_message.constraint_added'));

            $this->displayTable($tableName);
        }

    }
}
