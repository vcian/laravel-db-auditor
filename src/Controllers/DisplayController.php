<?php

namespace Vcian\LaravelDBAuditor\Controllers;

use Illuminate\Http\JsonResponse;
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
    public function getAudit() : JsonResponse
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
    public function getTableData(string $tableName) : JsonResponse
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
    public function getTableConstraint(string $tableName) : JsonResponse
    {

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

            $primaryKey = $indexing = $uniqueKey = $foreignKey = "-";

            if (in_array($table->COLUMN_NAME, $data['constrain']['primary'])) {
                $primaryKey = $greenKey;
            } else if (in_array($table->COLUMN_NAME, $data['constrain']['unique'])) {
                $uniqueKey = $grayKey;
            } else if (in_array($table->COLUMN_NAME, $data['constrain']['index'])) {
                $indexing = $grayKey;
            }

            foreach ($data['constrain']['foreign'] as $foreign) {
                if ($table->COLUMN_NAME === $foreign['column_name']) {
                    $foreignKeyToottip = '<div class="inline-flex">
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
                    $foreignKey = $foreignKeyToottip;
                }
            }

            $response[] = ["column" => $table->COLUMN_NAME, "primaryKey" =>  $primaryKey, "indexing" => $indexing, "uniqueKey" => $uniqueKey, "foreignKey" => $foreignKey];
        }

        return response()->json(array(
            "data" => $response
        ));
    }
}
