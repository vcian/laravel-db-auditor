<?php

namespace Vcian\LaravelDBAuditor\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;
use Vcian\LaravelDBAuditor\Traits\Rules;

class DisplayController
{
    use Rules, Audit;

    /**
     * Return view for audit
     * @return view
     */
    public function index()
    {
        $tables = $this->getTableList();
        return view('DBAuditor::auditor.pages.audit', compact('tables'));
    }

    /**
     * Get audit table list
     * @return JsonResponse
     */
    public function getAudit(): JsonResponse
    {
        $columnName = 'status';
        $noConstraint = '<img src=' . asset("auditor/icon/close.svg") . ' alt="key" class="m-auto" />';
        $constraint = '<img src=' . asset("auditor/icon/check.svg") . ' alt="key" class="m-auto" />';

        $tableRules = array_map(function ($value) use ($columnName, $noConstraint, $constraint) {
            if ($value[$columnName] === "") {
                $value[$columnName] = $noConstraint;
            } else {
                $value[$columnName] = $constraint;
            }
            return $value;
        }, $this->tablesRule());

        return response()->json(
            array(
                "data" => $tableRules
            )
        );
    }

    /**
     * Get table data
     * @param string $tableName
     * @return JsonResponse
     */
    public function getTableData(string $tableName): JsonResponse
    {
        return response()->json(array(
            "data" => $this->tableRules($tableName)
        ));
    }

    /**
     * Get Constraint list
     * @param string $tableName
     * @return JsonResponse
     */
    public function getTableConstraint(string $tableName): JsonResponse
    {

        $noConstraintFields = $this->getNoConstraintFields($tableName);
        $constraintList = $this->getConstraintList($tableName, $noConstraintFields);

        $data = [
            "fields" => $this->getFieldsDetails($tableName),
            'constrain' => [
                'primary' => $this->getConstraintField($tableName, Constant::CONSTRAINT_PRIMARY_KEY),
                'unique' => $this->getConstraintField($tableName, Constant::CONSTRAINT_UNIQUE_KEY),
                'foreign' => $this->getConstraintField($tableName, Constant::CONSTRAINT_FOREIGN_KEY),
                'index' => $this->getConstraintField($tableName, Constant::CONSTRAINT_INDEX_KEY)
            ]
        ];
        $response = [];
        $greenKey = '<img src=' . asset("auditor/icon/green-key.svg") . ' alt="key" class="m-auto" />';
        $grayKey = '<img src=' . asset("auditor/icon/gray-key.svg") . ' alt="key" class="m-auto" />';

        foreach ($data['fields'] as $table) {

            $primaryKey = $indexKey = $uniqueKey = $foreignKey = "-";


            if (in_array($table->COLUMN_NAME, $data['constrain']['primary'])) {
                $primaryKey = $greenKey;
            }

            if (in_array($table->COLUMN_NAME, $data['constrain']['unique'])) {
                $uniqueKey = $grayKey;
            }

            if (in_array($table->COLUMN_NAME, $data['constrain']['index'])) {
                $indexKey = $grayKey;
            }

            foreach ($data['constrain']['foreign'] as $foreign) {
                if ($table->COLUMN_NAME === $foreign) {
                    $foreignKeyTooltip = '<div class="inline-flex">
                    <img src=' . asset("auditor/icon/gray-key.svg") . ' alt="key" class="mr-2">
                    <div class="relative flex flex-col items-center group">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        <div class="absolute bottom-0 flex flex-col items-center hidden group-hover:flex">
                            <span class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg">Foreign Table Name : ' . $foreign['foreign_table_name'] . ' and Foreign Column Name : ' . $foreign['foreign_column_name'] . '</span>
                            <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                        </div>
                    </div>
                    </div>';
                    $foreignKey = $foreignKeyTooltip;
                }
            }

            foreach ($constraintList as $constraint) {
                switch ($constraint) {
                    case Constant::CONSTRAINT_PRIMARY_KEY:
                        if (in_array($table->COLUMN_NAME, $noConstraintFields['integer'])) {
                            $primaryKey = '<img src=' . asset("auditor/icon/add.svg") . ' alt="key" class="m-auto add-constraint-' . $table->COLUMN_NAME . '-' . Constant::CONSTRAINT_PRIMARY_KEY . '" style="height:30px;cursor: pointer;" onclick="add(`' . $table->COLUMN_NAME . '`, `' . Constant::CONSTRAINT_PRIMARY_KEY . '`)"/>';
                        }
                        break;
                    case Constant::CONSTRAINT_INDEX_KEY:
                        if (in_array($table->COLUMN_NAME, $noConstraintFields['mix'])) {
                            $indexKey = '<img src=' . asset("auditor/icon/add.svg") . ' alt="key" class="m-auto add-constraint-' . $table->COLUMN_NAME . '-' . Constant::CONSTRAINT_INDEX_KEY . '" style="height:30px;cursor: pointer;" onclick="add(`' . $table->COLUMN_NAME . '`, `' . Constant::CONSTRAINT_INDEX_KEY . '`)"/>';
                        }
                        break;
                    case Constant::CONSTRAINT_UNIQUE_KEY:
                        if (in_array($table->COLUMN_NAME, $noConstraintFields['mix'])) {
                            $fields = $this->getUniqueFields($tableName, $noConstraintFields['mix']);
                            if (in_array($table->COLUMN_NAME, $fields)) {
                                $uniqueKey = '<img src=' . asset("auditor/icon/add.svg") . ' alt="key" class="m-auto add-constraint-' . $table->COLUMN_NAME . '-' . Constant::CONSTRAINT_UNIQUE_KEY . '" style="height:30px;cursor: pointer;" onclick="add(`' . $table->COLUMN_NAME . '`, `' . Constant::CONSTRAINT_UNIQUE_KEY . '`)"/>';
                            }
                        }
                        break;
                    case Constant::CONSTRAINT_FOREIGN_KEY:
                        if(in_array($table->COLUMN_NAME, $noConstraintFields['integer'])) {
                            if(!$this->tableHasValue($tableName)) {
                                $foreignKey = '<img src=' . asset("auditor/icon/add.svg") . ' alt="key" class="m-auto add-constraint-'.$table->COLUMN_NAME.'-'.Constant::CONSTRAINT_FOREIGN_KEY.'" style="height:30px;cursor: pointer;" onclick="add(`'.$table->COLUMN_NAME.'`, `'.Constant::CONSTRAINT_FOREIGN_KEY.'`,`'.$tableName.'`)"/>';
                            }
                        }
                        break;
                    default:
                        break;
                }
            }

            $response[] = ["column" => $table->COLUMN_NAME, "primaryKey" => $primaryKey, "indexing" => $indexKey, "uniqueKey" => $uniqueKey, "foreignKey" => $foreignKey];
        }

        return response()->json(array(
            "data" => $response
        ));
    }

    /**
     * Update the field Constraint
     * @param Request
     * @return 
     */
    public function changeConstraint(Request $request): bool
    {
        $data = $request->all();
        $this->addConstraint($data['table_name'], $data['colum_name'], $data['constraint']);
        return Constant::STATUS_TRUE;
    }

    /**
     * Get Foreign Key Details
     * @return array
     */
    public function getForeignKeyTableList(): array
    {
        return $this->getTableList();
    }

    /**
     * Get Foreign Key Field List
     * @param string
     * @return array
     */
    public function getForeignKeyFieldList(string $tableName): array
    {
        return $this->getFieldsDetails($tableName);
    }

    /**
     * Add Foreign Key Constraint
     * @param Request
     * @return mixed
     */
    public function addForeignKeyConstraint(Request $request): mixed
    {
        $data= $request->all();

        if($data['reference_table'] === $data['table_name']) {
            return __('Lang::messages.constraint.error_message.foreign_selected_table_match', ['foreign' => $data['reference_table'], 'selected' => $data['table_name']]);
        }

        $referenceFieldType = $this->getFieldDataType($data['reference_table'], $data['reference_field']);
        $selectedFieldType = $this->getFieldDataType($data['table_name'], $data['select_field']);
        if ($referenceFieldType['data_type'] !== $selectedFieldType['data_type']) { 
            return __('Lang::messages.constraint.error_message.foreign_not_apply');
        }

        $this->addConstraint($data['table_name'], $data['select_field'], Constant::CONSTRAINT_FOREIGN_KEY, $data['reference_table'], $data['reference_field']);
        return Constant::STATUS_TRUE;
    }
}
