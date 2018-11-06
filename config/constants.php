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

    'certificatePlaceholders' => [
        'Course Name'           => '$certification->batch->course->course_name',
        'Descriptive Name'      => '',
        'University'            => '',
        'Center Code'           => '',
        'Course From'           => '',
        'Course Fee Amount'     => '',
        'Course From'           => '',
        'Student Name'          => '',
        'Student Address'       => '',
    ],
];