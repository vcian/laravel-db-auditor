<?php
// config for Vcian/LaravelDbAuditor

return [
    /*
    |
    |--------------------------------------------------------------------------
    | Skip tables
    |--------------------------------------------------------------------------
    |
    | Specify the tables that you want to skip auditing
    |
    |
    */

    'skip_tables' => [ // Add table name that you want to skip
        'cache',
        'sqlite_sequence',
        'migrations',
        'migrations_history',
        'sessions',
        'password_resets',
        'failed_jobs',
        'jobs',
        'queue_job',
        'queue_failed_jobs',
    ]
];
