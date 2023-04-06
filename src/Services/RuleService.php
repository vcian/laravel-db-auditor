<?php

namespace dbplayground\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use dbplayground\Constants\Constant;

class RuleService
{
    /**
     * @var array
     */
    protected $result = Constant::ARRAY_DECLARATION;

    /**
     * @param DBConnectionService
     * @param NamingRuleService
     * @param TableReadService
     */
    public function __construct(
        protected DBConnectionService $dBConnectionService,
        protected NamingRuleService $namingRuleService
    ) {
    }

    /**
     * Check table name rules
     * @return array
     */
    public function tablesRule(): array
    {
        $checkTableStatus = Constant::ARRAY_DECLARATION;
        try {
            $tableList = $this->dBConnectionService->getTableList();

            foreach ($tableList as $tableName) {
                $checkTableStatus[$tableName] = $this->checkRules($tableName, Constant::TABLE_RULES);
                $checkTableStatus[$tableName]['fields'] = $this->fieldRules($tableName);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $checkTableStatus;
    }

    /**
     * Check field rules
     * @param string $tableName
     * @return array
     */
    public function fieldRules($tableName): array
    {
        $checkFields = Constant::ARRAY_DECLARATION;
        try {
            $filedsDetails = $this->dBConnectionService->getFields($tableName);
            foreach ($filedsDetails as $field) {
                $checkFields[] = $this->checkRules($field, Constant::FIELD_RULES);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $checkFields;
    }

    /**
     * Check Rules for Fields and Tables
     * @param string $name
     * @param string $type
     * @return array
     */
    public function checkRules($name, $type = null): array
    {
        $messages = Constant::ARRAY_DECLARATION;
        try {
            $checkLowerCase = $this->namingRuleService->nameOnlyLowerCase($name);
            $checkSpace = $this->namingRuleService->nameHasNoSpace($name);
            $checkAlphabets = $this->namingRuleService->nameHasOnlyAlphabets($name);

            if ($type === Constant::TABLE_RULES) {
                $checkLength = $this->namingRuleService->nameHasFixLength($name);
                $checkNamePlural = $this->namingRuleService->nameAlwaysPlural($name);

                if (!$checkLength) {
                    $messages[] = '✗ Names should be not more than 64 characters.';
                }

                if (!$checkNamePlural) {
                    $messages[] = '✗ Use Table Name Plural.';
                }
            }

            if (!$checkSpace) {
                $messages[] = '✗ Using space between words is not advised. Please Use Underscore.';
            }

            if (!$checkAlphabets) {
                $messages[] = '✗ Numbers are not for names! Please use alphabets for name.';
            }


            if (!$checkLowerCase) {
                $messages[] = '✗ Use lowercase MYSQL is case sensitive.';
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return ["name" => $name, "status" => $messages];
    }

    /**
     * Check rules for single table and check table exist or not
     * @param string $tableName
     * @return array
     */
    public function tableRules($tableName)
    {
        $checkTableStatus = Constant::ARRAY_DECLARATION;
        try {
            if ($tableName) {
                $tableExist = $this->dBConnectionService->checkTableExist($tableName);
                if (!$tableExist) {
                    return false;
                }
                $checkTableStatus[$tableName] = $this->checkRules($tableName, Constant::TABLE_RULES);
                $checkTableStatus[$tableName]['fields'] = $this->fieldRules($tableName);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $checkTableStatus;
    }
}
