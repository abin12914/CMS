<?php
//use extra caution while edit cause it may affect multiple parts of the project
//never think of changing values in production, ever!!
return [
    'userRoles' => [
        'superAdmin' => 0,
        'admin'      => 1,
        'user'       => 2,
    ],

    'genderTypes' => [
        -1 => 'Not Specified',
        1 => 'Male',
        2 => 'Female',
    ],

    'courseDurationTypes' => [
        1 => 'Month',
        2 => 'Semester',
        3 => 'Year',
    ],

    'certificateRelations' => [
        'course' => [
            'relationName'  => 'purchase',
            'displayName'   => 'Purchase'
        ],
        2 => [
            'relationName'  => 'employeeWage',
            'displayName'   => 'Employee Wage'
        ],
        3 => [
            'relationName'  => 'sale',
            'displayName'   => 'Sale'
        ],
        4 => [
            'relationName'  => 'transportation',
            'displayName'   => 'Transportation'
        ],
        5 => [
            'relationName'  => 'expense',
            'displayName'   => 'Expense'
        ],
        6 => [
            'relationName'  => 'voucher',
            'displayName'   => 'Voucher'
        ]
    ],
];