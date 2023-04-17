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
            'table_selection' => 'Which table whould you like to audit?',
            'continue' => 'Do you want add more constrain?',
            'constraint_selection' => 'Please select constrain which you want to add',
            'field_selection' => 'Please select field where you want to add',
            'foreign_table' => 'Please add foreign table name',
            'foreign_field' => 'Please add foreign table primary key name',
        ],
        'messages' => [
            'success' => 'Constraint Add Successfully'
        ]
    ],

    'standard' => [
        'error_messages' => [
            'length' => 'Names should be not more than 64 characters.',
            'plural' => 'Use Table Name Plural.',
            'space' => 'Using space between words is not advised. Please Use Underscore.',
            'alphabets' => 'Numbers are not for names! Please use alphabets for name.',
            'lowercase' => 'Use lowercase MYSQL is case sensitive.'
        ]
    ]

];
