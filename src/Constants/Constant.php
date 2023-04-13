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

    //constrain list
    public const CONSTRAIN_PRIMARY_KEY = "PRIMARY";
    public const CONSTRAIN_INDEX_KEY = "INDEX";
    public const CONSTRAIN_UNIQUE_KEY = "UNIQUE";
    public const CONSTRAIN_FOREIGN_KEY = "FOREIGN";
    public const CONSTRAIN_ALL_KEY = "ALL";

    //Header Title
    public const HEADER_TITLE_TABLE_NAME = "Table Name";
    public const HEADER_TITLE_COLUMN_NAME = "Column Name";
    public const HEADER_TITLE_CONSTRAIN = "Constraints";
    public const HEADER_TITLE_REFERENCED_TABLE_NAME = "Reference Table Name";
    public const HEADER_TITLE_REFERENCED_COLUMN_NAME = "Reference Column Name";

    //rules
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

}
