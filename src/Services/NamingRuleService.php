<?php

namespace Vcian\LaravelDBAuditor\Services;

use Vcian\LaravelDBAuditor\Constants\Constant;
use Illuminate\Support\Str;

class NamingRuleService
{

    /**
     * Check name only in lowercase.
     * @param string $name
     * @return mixed
     */
    public function nameOnlyLowerCase(string $name) : mixed
    {
        $name = $this->removeSpecialCharacter($name);
        if (strtolower($name) !== $name) {
            return $this->addSpecialCharacter(strtolower($name));
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has no space.
     * @param string $name
     * @return mixed
     */
    public function nameHasNoSpace(string $name) : mixed
    {
        $name == trim($name);
        if (str_contains($name, ' ')) {
            return $this->addSpecialCharacter($name);
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name only in alphabets.
     * @param string $tableName
     * @return mixed
     */
    public function nameHasOnlyAlphabets(string $tableNames) : mixed
    {
        $name = $this->removeSpecialCharacter($tableNames);
        if (!ctype_alpha($name)) {
            return $this->addSpecialCharacter(preg_replace('/[0-9]+/', '', $tableNames));
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
    public function nameHasNoPrefix(string $tableNames) : mixed
    {
        $nameIdentfy = explode('_', $tableNames);

        $name = $nameIdentfy[0];
        if (strtolower($name) === Constant::PREFIX_STRING) {
            return $nameIdentfy[1];
        }

        return Constant::STATUS_TRUE;
    }

    /**
     * Check name only in plural.
     * @param string $tableNames
     * @return mixed
     */
    public function nameAlwaysPlural(string $tableNames) : mixed
    {
        $pluralName = Str::plural($tableNames);
        if ($tableNames !== $pluralName) {
            return $pluralName;
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
        return str_replace("_", '', $name);
    }

    /**
     * Add special character
     * @param string $name
     * @return string
     */
    public function addSpecialCharacter(string $name) : string
    {
        return str_replace(" ", '_', $name);
    }
}
