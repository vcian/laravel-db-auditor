<?php

namespace Vcian\LaravelDBAuditor\Services;

use Illuminate\Support\Str;
use Vcian\LaravelDBAuditor\Constants\Constant;

class NamingRuleService
{
    /**
     * Check name only in lowercase.
     * @param string $name
     * @return string|bool
     */
    public function nameOnlyLowerCase(string $name): string|bool
    {
        $name = $this->removeSpecialCharacter($name);
        if (strtolower($name) !== $name) {
            return $this->addSpecialCharacter(strtolower($name));
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Remove underscore from name.
     * @param string $name
     * @return string
     */
    public function removeSpecialCharacter(string $name): string
    {
        return str_replace("_", '', $name);
    }

    /**
     * Add special character
     * @param string $name
     * @return string
     */
    public function addSpecialCharacter(string $name): string
    {
        return str_replace(" ", '_', $name);
    }

    /**
     * Check name has no space.
     * @param string $name
     * @return string|bool
     */
    public function nameHasNoSpace(string $name): string|bool
    {
        if (str_contains($name, ' ')) {
            return $this->addSpecialCharacter($name);
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Check name only in alphabets.
     * @param string $name
     * @return string|bool
     */
    public function nameHasOnlyAlphabets(string $name): string|bool
    {
        $name = str_replace(' ', '', $this->removeSpecialCharacter($name));
        if (!ctype_alpha($name)) {
            return $this->addSpecialCharacter(preg_replace(Constant::NUMERIC_PATTERN, '', $name));
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has fix length.
     * @param string $name
     * @return bool
     */
    public function nameHasFixLength(string $name): bool
    {
        if (strlen($name) >= Constant::NAME_LENGTH) {
            return Constant::STATUS_FALSE;
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has no prefix.
     * @param string $tableNames
     * @return string|bool
     */
    public function nameHasNoPrefix(string $tableNames): string|bool
    {
        $nameIdentify = explode('_', $tableNames);
        $name = $nameIdentify[0];
        if (strtolower($name) === Constant::PREFIX_STRING) {
            return $nameIdentify[1];
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Check name only in plural.
     * @param string $tableNames
     * @return string|bool
     */
    public function nameAlwaysPlural(string $tableNames): string|bool
    {
        $pluralName = Str::plural($tableNames);
        if ($tableNames !== $pluralName) {
            return $pluralName;
        }
        return Constant::STATUS_TRUE;
    }
}
