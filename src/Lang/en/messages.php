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
            'constraint_selection' => 'Please select a constraint which you want to add',
            'field_selection' => 'Please select a field to add constraint',
            'foreign_table' => 'Please add foreign table name',
            'foreign_field' => 'Please add foreign table primary key name',
        ],
        'success_message' => [
            'constraint_added' => 'Constraint Added Successfully'
        ]
    ],
    'standard' => [
        'error_message' => [
            'length' => 'Table name should not be more than 64 characters.',
            'plural' => 'Table name should be Plural.',
            'space' => 'Using space between words is not advised. Please Use Underscore "_".',
            'alphabets' => 'Numbers are not for names and is not advised! Please use alphabets for name.',
            'lowercase' => 'Name should be lowercase.'
        ]
    ]

];
