<?php

namespace Vcian\LaravelDBPlayground\Services;

use Vcian\LaravelDBPlayground\Constants\Constant;
use Illuminate\Support\Str;

class NamingRuleService
{

    /**
     * Check name only in lowercase.
     * @param string $name
     */
    public function nameOnlyLowerCase(string $name)
    {
        $name = $this->removeUnderScore($name);
        if (strtolower($name) !== $name) {
            return false;
        }

        return true;
    }

    /**
     * Check name has no space.
     * @param string $name
     */
    public function nameHasNoSpace(string $name)
    {
        $name == trim($name);
        if (str_contains($name, ' ')) {
            return false;
        }

        return true;
    }

    /**
     * Check name only in alphabets.
     * @param string $tableName
     */
    public function nameHasOnlyAlphabets(string $tableNames)
    {
        $name = str_replace(' ', '', $this->removeUnderScore($tableNames));
        if (!ctype_alpha($name)) {
            return false;
        }

        return true;
    }

    /**
     * Check name has fix length.
     * @param string $name
     */
    public function nameHasFixLength(string $name)
    {
        if (strlen($name) >= Constant::NAME_LENGTH) {
            return false;
        }

        return true;
    }

    /**
     * Check name has no prefix.
     * @param string $name
     */
    public function nameHasNoPrefix(string $tableNames)
    {
        $nameIdentfy = explode('_', $tableNames);

        $name = $nameIdentfy[0];
        if (strtolower($name) === Constant::PREFIX_STRING) {
            return false;
        }

        return true;
    }

    /**
     * Check name only in plural.
     * @param string $tableNames
     */
    public function nameAlwaysPlural(string $tableNames)
    {
        $pluralName = Str::plural($tableNames);
        if ($tableNames !== $pluralName) {
            return false;
        }

        return true;
    }

    /**
     * Remove underscore from name.
     * @param string $name
     */
    public function removeUnderScore(string $name)
    {
        return str_replace("_", '', $name);
    }
}
