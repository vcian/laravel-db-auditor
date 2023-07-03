<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Vcian\LaravelDBAuditor\Constants\Constant;

trait NamingRules
{
    public string $conventionName;


    public function setConvenationName(string $name)
    {
        $this->conventionName = $name;
    }

    /**
     * Check name only in lowercase. or camelCase or snake_case
     * @param string $name
     * @return string|bool
     */
    public function nameConvention(): string|bool
    {
        if (str_contains($this->conventionName, " ")) {
            return $this->convertToSnakeCase($this->conventionName);
        }

        if ($this->isLowerCase($this->conventionName) || $this->isCamelCase($this->conventionName) || $this->isSnakeCase($this->conventionName)) {
            return Constant::STATUS_TRUE;
        }

        return strtolower($this->conventionName);
    }

    /**
     * Check name only in alphabets.
     * @param string $name
     * @return string|bool
     */
    public function nameHasAlphabetCharacterSet(): string|bool
    {
        if (preg_match('/^[A-Za-z$#_\s]+$/', $this->conventionName)) {
            return Constant::STATUS_TRUE;
        } else {
            return preg_replace('/[^A-Za-z$#_]/', '', $this->conventionName);
        }
    }

    /**
     * Check name has fix length.
     * @param string $name
     * @return bool
     */
    public function nameHasFixLength(): bool
    {
        if (strlen($this->conventionName) >= Constant::NAME_LENGTH) {
            return Constant::STATUS_FALSE;
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Check name has no prefix.
     * @param string $tableNames
     * @return string|bool
     */
    public function nameHasNoPrefix(): string|bool
    {
        $nameIdentify = explode('_', $this->conventionName);
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
    public function nameAlwaysPlural(): string|bool
    {
        $pluralName = Str::plural($this->conventionName);
        if ($this->conventionName !== $pluralName) {
            return strtolower($pluralName);
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Check Name is snakeCase or not
     * @param string $name
     * @return bool
     */
    public function isCamelCase(string $name)
    {
        return preg_match('/^[a-z][a-zA-Z0-9]*$/', $name) && preg_match('/[A-Z]/', $name);
    }

    /**
     * Check Name is snake_case or not
     * @param string $name
     * @return bool
     */
    public function isSnakeCase(string $name): bool
    {
        return preg_match('/^[a-z0-9_]*$/', $name) && strpos($name, '_') !== false;
    }

    /**
     * Check Name is lowercase or not
     * @param string $name
     * @return bool
     */
    public function isLowerCase(string $name): bool
    {
        return $name === strtolower($name);
    }

    /**
     * Convert string to snake case
     * @param string $name
     * @return string
     */
    public function convertToSnakeCase(string $name): string
    {
        $snakeCase = str_replace(' ', '_', $name);
        $snakeCase = strtolower($snakeCase);
        return $snakeCase;
    }
}
