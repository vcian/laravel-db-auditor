<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'audit' => [],

    'constraint' => [
        'question' => [
            'table_selection' => 'Which table would you like to audit?',
            'continue' => 'Do you want add more constraint?',
            'constraint_selection' => 'Please select a constraint which you want to add.',
            'field_selection' => 'Please select a field to add constraint',
            'foreign_table' => 'Please add foreign table name.',
            'foreign_field' => 'Please add primary key name of foreign table.',
        ],
        'success_message' => [
            'constraint_added' => 'Congratulations! Constraint Added Successfully.'
        ],
        'error_message' => [
            'constraint_not_apply' => 'Can not apply :constraint key | Please truncate table.',
            'foreign_not_apply' => 'Columns must have the same datatype.',
            'table_not_found' => 'Foreign table not found.',
            'field_not_found' => 'Foreign field not found.',
            'foreign_selected_table_match' => "Can't add constraint because :selected table and foreign :foreign table are same. Please use different table name.",
            'unique_constraint_not_apply' => "All field values are duplicate. You can't add unique constraint.",
        ]
    ],
    'standard' => [
        'error_message' => [
            'length' => 'Table name should not be more than 64 characters.',
            'plural' => 'Table name should be plural.',
            'space' => 'Space between words is not advised. Please Use Underscore "_"',
            'alphabets' => 'Numbers are not for names and is not advised! Please use alphabets for name.',
            'lowercase' => 'Name should be in lowercase.',
            'datatype_change' => 'Here you can use CHAR datatype instead of VARCHAR if data values in a column are of the same length.',
        ],
        'question' => [
            'table_selection' => 'Please enter table name if you want to see the table report',
        ]
    ]
];
