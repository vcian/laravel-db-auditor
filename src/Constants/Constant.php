<?php

namespace Vcian\LaravelDBAuditor\Constants;

/**
 * Class Constants
 * @package dbauditor\Constants
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
    public const NULL = null;
    public const NUMERIC_PATTERN = '/[0-9]+/';

    // Datatype List
    public const DATATYPE_VARCHAR = 'varchar';
    public const DATATYPE_STRING = 'string';
    public const DATATYPE_INT = 'int';
    public const DATATYPE_INTEGER = 'integer';

    public const RESTRICT_DATATYPE = [
        'timestamp',
        'date',
        'datetime',
        'json',
        'text',
        'longtext',
        'mediumtext'
    ];

}
