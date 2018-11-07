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
        '[[CourseName]]'       => 'batch->course->course_name',
        '[[DescriptiveName]]'  => 'batch->course->descriptive_name',
        '[[University]]'       => 'batch->course->university',
        '[[CenterCode]]'       => 'batch->course->center_code',
        '[[CourseFrom]]'       => 'batch->from_year',
        '[[CourseTo]]'         => 'batch->to_year',
        '[[CourseFeeAmount]]'  => 'batch->fee_amount',
        '[[StudentName]]'      => 'name',
        '[[StudentAddress]]'   => 'address',
    ],
];