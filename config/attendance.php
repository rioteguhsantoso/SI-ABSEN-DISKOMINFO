<?php

return [
    'office_lat' => env('OFFICE_LAT'),
    'office_lng' => env('OFFICE_LNG'),
    'radius' => (int) env('OFFICE_RADIUS', 100),
    'max_accuracy' => (int) env('OFFICE_MAX_ACCURACY', 150),
    'test_mode' => env('ATTENDANCE_TEST_MODE', false),
    'test_ignore_day' => env('ATTENDANCE_TEST_IGNORE_DAY', false),
    'test_ignore_time' => env('ATTENDANCE_TEST_IGNORE_TIME', false),
];
