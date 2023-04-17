<?php

namespace Vcian\LaravelDBAuditor\Constants;

/**
 * Class Constants
 * @package dbplayground\Constants
 */
class Constant
{
    public const ARRAY_DECLARATION = [];

    public const DASH = " - ";

    //constraint list
    public const CONSTRAINT_PRIMARY_KEY = "PRIMARY";
    public const CONSTRAINT_INDEX_KEY = "INDEX";
    public const CONSTRAINT_UNIQUE_KEY = "UNIQUE";
    public const CONSTRAINT_FOREIGN_KEY = "FOREIGN";

    public const NAME_LENGTH = 64;

    public const STATUS_TRUE_EMOJI = "✓";
    public const STATUS_FALSE_EMOJI = "✗";

    public const PREFIX_STRING = 'tbl';

    public const TABLE_RULES = 'table';
    public const FIELD_RULES = 'field';

    public const INDEX_FILE_NAME = 'update_table_index.php';
    public const UNIQUE_FILE_NAME = 'update_table_unique.php';
    public const PRIMARY_FILE_NAME = 'update_table_primary.php';
    public const FOREIGN_FILE_NAME = 'update_table_foreign.php';

    public const STATUS_TRUE = true;
    public const STATUS_FALSE = false;

    public const STANDARD_COMMAND = 'STANDARD';
    public const CONSTRAINT_COMMAND = 'CONSTRAINT';
}
