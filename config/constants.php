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
        1 => 'Male',
        2 => 'Female',
    ],

    'studentTitles' => [
        1 => 'Mr.',
        2 => 'Ms',
        3 => 'Mrs',
        4 => 'M/S',
    ],

    'courseDurationTypes' => [
        1 => 'Month',
        2 => 'Semester',
        3 => 'Year',
    ],

    'certificatePlaceholders' => [
        "[[CourseName]]"                => 'batch->course->course_name',
        "[[DescriptiveName]]"           => 'batch->course->descriptive_name',
        "[[University]]"                => 'batch->course->university->university_name',
        "[[CenterCode]]"                => 'batch->course->center_code',
        "[[CourseFrom]]"                => 'batch->from_year',
        "[[CourseTo]]"                  => 'batch->to_year',
        "[[CourseDuration]]"            => 'batch->course->duration',
        "[[ClassStartDate]]"            => 'batch->class_start_date',
        "[[CourseFeeAmount]]"           => 'batch->fee_amount',
        "[[CourseFeePerYear]]"          => 'batch->fee_per_year',
        "[[CourseFeePerSem]]"           => 'batch->fee_per_sem',
        "[[CourseFeePerMonth]]"         => 'batch->fee_per_month',
        "[[StudentName]]"               => 'name',
        "[[StudentAddress]]"            => 'address',
        "[[StudentRegistrationNumber]]" => 'registration_number',
        "[[UniversityGrade]]"           => 'batch->course->university->university_grade',
    ],
];