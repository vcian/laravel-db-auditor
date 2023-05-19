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
        $inputName = $this->removeSpecialCharacter($name);
        if (strtolower($inputName) !== $inputName) {
            return strtolower($this->addSpecialCharacter($name));
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
            return strtolower($this->addSpecialCharacter($name));
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
        $title = str_replace(' ', '', $this->removeSpecialCharacter($name));
        if (!ctype_alpha($title)) {
            $result = $this->addSpecialCharacter(preg_replace(Constant::NUMERIC_PATTERN, '', $name));
            return strtolower((strpos($result, "_") === strlen($result)-1 )? substr_replace($result ,"", -1) : $result);
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
            return strtolower($nameIdentify[1]);
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
            return strtolower($pluralName);
        }
        return Constant::STATUS_TRUE;
    }
}
