<?php

namespace Vcian\LaravelDBAuditor\Services;

use Vcian\LaravelDBAuditor\Constants\Constant;
use Illuminate\Support\Str;

class NamingRuleService
{

    /**
     * Check name only in lowercase.
     * @param string $name
     * @return bool
     */
    public function nameOnlyLowerCase(string $name) : bool
    {
        $name = $this->removeSpecialCharacter($name);
        if (strtolower($name) !== $name) {
            return Constant::STATUS_FALSE;
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has no space.
     * @param string $name
     * @return bool
     */
    public function nameHasNoSpace(string $name) : bool
    {
        $name == trim($name);
        if (str_contains($name, ' ')) {
            return Constant::STATUS_FALSE;
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name only in alphabets.
     * @param string $tableName
     * @return bool
     */
    public function nameHasOnlyAlphabets(string $tableNames) : bool
    {
        $name = $this->removeSpecialCharacter($tableNames);
        if (!ctype_alpha($name)) {
            return Constant::STATUS_FALSE;
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has fix length.
     * @param string $name
     * @return bool
     */
    public function nameHasFixLength(string $name) : bool
    {
        if (strlen($name) >= Constant::NAME_LENGTH) {
            return Constant::STATUS_FALSE;
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has no prefix.
     * @param string $name
     * @return bool
     */
    public function nameHasNoPrefix(string $tableNames) : bool
    {
        $nameIdentfy = explode('_', $tableNames);

        $name = $nameIdentfy[0];
        if (strtolower($name) === Constant::PREFIX_STRING) {
            return Constant::STATUS_FALSE;
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name only in plural.
     * @param string $tableNames
     * @return bool
     */
    public function nameAlwaysPlural(string $tableNames) : bool
    {
        $pluralName = Str::plural($tableNames);
        if ($tableNames !== $pluralName) {
            return Constant::STATUS_FALSE;
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Remove underscore from name.
     * @param string $name
     * @return string
     */
    public function removeSpecialCharacter(string $name) : string
    {
        return str_replace(' ', '', str_replace("_", '', $name));
    }
}
