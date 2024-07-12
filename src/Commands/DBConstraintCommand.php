<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Termwind\{renderUsing};
use function Termwind\{render};

class DBConstraintCommand extends Command
{
    use Audit;
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

            $tableName = select(
                label: __('Lang::messages.constraint.question.table_selection'),
                options: $this->getTableList(),
                default: $this->getTableList()[0]
            );

            $this->displayTable($tableName);

            if ($tableName) {

                $continue = Constant::STATUS_TRUE;

                do {
                    $noConstraintFields = $this->getNoConstraintFields($tableName);

                    if (empty($noConstraintFields)) {
                        $continue = Constant::STATUS_FALSE;
                    } else {
                        if (confirm(label: __('Lang::messages.constraint.question.continue'))) {

                            $this->skip = Constant::STATUS_FALSE;
                            $constraintList = $this->getConstraintList($tableName, $noConstraintFields);
                            $selectConstrain = select(
                                label: __('Lang::messages.constraint.question.constraint_selection'),
                                options: $constraintList,
                                default: $constraintList[0]
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
        }

        return self::SUCCESS;
    }

    /**
     * Display selected table
     */
    public function displayTable(string $tableName): void
    {

        $data = [
            'table' => $tableName,
            'size' => $this->getTableSize($tableName),
            'fields' => $this->getFieldsDetails($tableName),
            'field_count' => count($this->getFieldsDetails($tableName)),
            'constrain' => [
                'primary' => $this->getConstraintField($tableName, Constant::CONSTRAINT_PRIMARY_KEY),
                'unique' => $this->getConstraintField($tableName, Constant::CONSTRAINT_UNIQUE_KEY),
                'foreign' => $this->getConstraintField($tableName, Constant::CONSTRAINT_FOREIGN_KEY),
                'index' => $this->getConstraintField($tableName, Constant::CONSTRAINT_INDEX_KEY),
            ],
        ];

        render(view('DBAuditor::constraint', ['data' => $data]));
    }

    /**
     * Display error messages
     */
    public function errorMessage(string $message): void
    {
        $this->skip = Constant::STATUS_TRUE;
        render(view('DBAuditor::error_message', ['message' => $message]));
    }

    /**
     * Display success messages
     */
    public function successMessage(string $message): void
    {
        render(view('DBAuditor::success_message', ['message' => $message]));
    }

    /**
     * Get Foreign Key Constrain
     */
    public function foreignKeyConstraint(string $tableName, string $selectField): void
    {
        $foreignContinue = Constant::STATUS_FALSE;
        $referenceField = Constant::NULL;
        $fields = Constant::ARRAY_DECLARATION;

        do {
            $referenceTable = $this->anticipate(__('Lang::messages.constraint.question.foreign_table'), $this->getTableList());

            if ($referenceTable && $this->checkTableExist($referenceTable)) {

                foreach ($this->getFieldsDetails($referenceTable) as $field) {
                    $fields[] = $field->COLUMN_NAME;
                }
                do {
                    $referenceField = $this->anticipate(__('Lang::messages.constraint.question.foreign_field'), $fields);

                    if (! $referenceField || ! $this->checkFieldExistOrNot($referenceTable, $referenceField)) {
                        $this->errorMessage(__('Lang::messages.constraint.error_message.field_not_found'));
                    } else {
                        $foreignContinue = Constant::STATUS_TRUE;
                    }
                } while ($foreignContinue === Constant::STATUS_FALSE);
            } else {
                $this->errorMessage(__('Lang::messages.constraint.error_message.table_not_found'));
            }
        } while ($foreignContinue === Constant::STATUS_FALSE);

        $referenceFieldType = $this->getFieldDataType($referenceTable, $referenceField);
        $selectedFieldType = $this->getFieldDataType($tableName, $selectField);

        if ($referenceTable === $tableName) {
            $this->errorMessage(__('Lang::messages.constraint.error_message.foreign_selected_table_match', ['foreign' => $referenceTable, 'selected' => $tableName]));
        }

        if (! $this->skip) {
            if ($referenceFieldType['data_type'] !== $selectedFieldType['data_type']) {

                render('
                <div class="mt-1">
                    <div class="flex space-x-1">
                        <span class="font-bold text-green">'.$selectedFieldType['data_type'].'</span>
                        <i class="text-blue">'.$selectField.'</i>
                        <span class="flex-1 content-repeat-[.] text-gray"></span>
                        <i class="text-blue">'.$referenceField.'</i>
                        <span class="font-bold text-green">'.$referenceFieldType['data_type'].'</span>
                    </div>
                </div>
                ');
                $this->errorMessage(__('Lang::messages.constraint.error_message.foreign_not_apply'));
            } else {
                $this->addConstraint($tableName, $selectField, Constant::CONSTRAINT_FOREIGN_KEY, $referenceTable, $referenceField);
            }
        }
    }

    public function selectedConstraint(string $selectConstrain, array $noConstraintFields, string $tableName): void
    {

        if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
            $tableHasValue = $this->tableHasValue($tableName);

            if ($tableHasValue) {
                $this->errorMessage(__('Lang::messages.constraint.error_message.constraint_not_apply', ['constraint' => strtolower($selectConstrain)]));
            }
        }

        if (! $this->skip) {
            if ($selectConstrain === Constant::CONSTRAINT_PRIMARY_KEY || $selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                $fields = $noConstraintFields['integer'];
            } else {
                $fields = $noConstraintFields['mix'];
            }

            if ($selectConstrain === Constant::CONSTRAINT_UNIQUE_KEY) {
                $fields = $this->getUniqueFields($tableName, $noConstraintFields['mix']);
                if (empty($fields)) {
                    $this->errorMessage(__('Lang::messages.constraint.error_message.unique_constraint_not_apply'));
                }
            }

            if (! $this->skip) {
                $selectField = $this->choice(
                    __('Lang::messages.constraint.question.field_selection').' '.strtolower($selectConstrain).' key',
                    $fields
                );

                if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                    $this->foreignKeyConstraint($tableName, $selectField);
                } else {
                    $this->addConstraint($tableName, $selectField, $selectConstrain);
                }
            }
        }

        if (! $this->skip) {
            renderUsing($this->output);

            $this->successMessage(__('Lang::messages.constraint.success_message.constraint_added'));

            $this->displayTable($tableName);
        }
    }
}
