<?php

namespace Vcian\LaravelDBAuditor\Constants;

/**
 * Class Constants
 */
class Constant
{
    public const ARRAY_DECLARATION = [];

    public const DASH = ' - ';

    //constraint list
    public const CONSTRAINT_PRIMARY_KEY = 'PRIMARY';

    public const CONSTRAINT_INDEX_KEY = 'INDEX';

    public const CONSTRAINT_UNIQUE_KEY = 'UNIQUE';

    public const CONSTRAINT_FOREIGN_KEY = 'FOREIGN';

    public const NAME_LENGTH = 64;

    public const STATUS_TRUE_EMOJI = '✓';

    public const STATUS_FALSE_EMOJI = '✗';

    public const PREFIX_STRING = 'tbl';

    public const TABLE_RULES = 'table';

    public const FIELD_RULES = 'field';

    public const MYSQL_DB = 'mysql';

    public const SQLITE_DB = 'sqlite';

    public const POSTGRESQL_DB = 'pgsql';

    public const UNIQUE_RULES = 'unique';

    public const INDEX_FILE_NAME = 'update_table_index.php';

    public const UNIQUE_FILE_NAME = 'update_table_unique.php';

    public const PRIMARY_FILE_NAME = 'update_table_primary.php';

    public const FOREIGN_FILE_NAME = 'update_table_foreign.php';

    public const STATUS_TRUE = true;

    public const STATUS_FALSE = false;

    public const STANDARD_COMMAND = 'STANDARD';

    public const CONSTRAINT_COMMAND = 'CONSTRAINT';

    public const SUMMARY_COMMAND = 'SUMMARY';

    public const TRACK_COMMAND = 'TRACK';

    public const CHECK_PERFORMANCE_PARAMETER_COMMAND = 'CHECK PERFORMANCE PARAMETER';

    public const NULL = null;

    public const NUMERIC_PATTERN = '/[0-9]+/';

    // Datatype List
    public const MYSQL_DATATYPE_TO_LARAVEL_DATATYPE = [
        'varchar' => 'string',
        'int' => 'integer',
        'bigint' => 'bigInteger',
        'bool' => 'boolean',
        'tinyint' => 'tinyInteger',
        'smallint' => 'smallInteger',
        'mediumint' => 'mediumInteger',
    ];

    public const DATATYPE_VARCHAR = 'varchar';

    public const DATATYPE_VARCHAR_SIZE = '255';

    public const RESTRICT_DATATYPE = [
        'timestamp',
        'date',
        'datetime',
        'json',
        'text',
        'longtext',
        'mediumtext',
        'enum',
        'float',
        'double',
    ];

    public const NUMERIC_DATATYPE = [
        'int',
        'bigint',
        'tinyint',
        'smallint',
        'mediumint',
        'decimal',
    ];

    public const DATATYPE_DECIMAL = 'decimal';

    public const CREATE = 'Create';

    public const UPDATE = 'Update';

    public const NOT_DEFINE = 'Not Define';

    public const PENDING = 'Pending';

    public const MIGRATED = 'Migrated';

    public const TABLE = 'table';

    public const ACTION = 'action';

    public const STATUS = 'status';

    public const DEFAULT_SIZE = '0.00';

    public const BYTES_IN_GB = 1073741824;
    public const BYTES_IN_MB = 1048576;
    public const BYTES_IN_KB = 1024;

    public const GB = 'GB';
    public const MB = 'MB';
    public const KB = 'KB';
}
