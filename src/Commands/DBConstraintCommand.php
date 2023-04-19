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
                                    
                                    $this->errorMessage(__('Lang::messages.constraint.error_message.constraint_not_apply', ['constraint' => strtolower($selectConstrain)]));
                                }
                            }

                            if ($continue) {

                                if ($selectConstrain === Constant::CONSTRAINT_PRIMARY_KEY || $selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                                    $fields = $noConstrainfields['integer'];
                                } else {
                                    $fields = $noConstrainfields['mix'];
                                }

                                $selectField = $this->choice(
                                    __('Lang::messages.constraint.question.field_selection') . ' ' . strtolower($selectConstrain) . ' key',
                                    $fields
                                );

                                if ($selectConstrain === Constant::CONSTRAINT_FOREIGN_KEY) {
                                    $this->foreignKeyConstraint($tableName, $selectField);
                                } else {
                                    $auditService->addConstraint($tableName, $selectField, $selectConstrain);
                                }

                                renderUsing($this->output);

                                $this->successMessage(__('Lang::messages.constraint.success_message.constraint_added'));

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
     * @return void
     */
    public function displayTable(string $tableName) :  void
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

    /**
     * Display success messages
     * @param string $message
     */
    public function successMessage(string $message) : void
    {
        render('<pre class="text-green">
                               .-"""-.
                              / .===. \
                              \/ 6 6 \/
                              ( \___/ )
          _________________ooo_\_____/______________________
         /                                                  \
        |   '. $message .'   |
         \______________________________ooo_________________/
                               |  |  |
                               |_ | _|
                               |  |  |
                               |__|__|
                               /-`Y`-\
                              (__/ \__)
        </pre>');
    }

    /**
     * Display error messages
     * @param string $message
     */
    public function errorMessage(string $message) : void
    {
        render('<pre class="text-center text-red">
        *********************************************************************
                                        
                    '. $message .'      

        *********************************************************************
        </pre>');
    }

    public function foreignKeyConstraint(string $tableName, string $selectField)
    {
        $auditService = resolve(AuditService::class);
     
        $referenceTable = $this->ask(__('Lang::messages.constraint.question.foreign_table'));
            
        if($auditService->checkTableExistOrNot($referenceTable)) {
            $referenceField = $this->ask(__('Lang::messages.constraint.question.foreign_field'));
            if(!$auditService->checkFieldExistOrNot($referenceTable, $referenceField)) {
                $this->errorMessage("Foreign field not found.");
            } 
        } else {
            $this->errorMessage("Foreign table not found.");
        }
        
        $referenceFieldType = $auditService->getFieldDataType($referenceTable, $referenceField);
        $selectedFieldType = $auditService->getFieldDataType($tableName, $selectField);
        
        if($referenceFieldType !== $selectedFieldType) {

            render(' 
            <div class="mt-1">
                <div class="flex space-x-1">
                    <span class="font-bold text-green">'.$selectedFieldType.'</span>
                    <i class="text-blue">'. $selectField .'</i>
                    <span class="flex-1 content-repeat-[.] text-gray"></span>
                    <i class="text-blue">'. $referenceField .'</i>
                    <span class="font-bold text-green">'. $referenceFieldType .'</span>
                </div>
            </div>
            ');

            return $this->errorMessage(__('Lang::messages.constraint.error_message.foreign_not_apply')); 
        }
        
        $auditService->addConstraint($tableName, $selectField, Constant::CONSTRAINT_FOREIGN_KEY, $referenceTable, $referenceField);
    }
}
