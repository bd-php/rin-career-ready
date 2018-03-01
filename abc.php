<?php

$json = array(
    'name' => 'amran',
    'address' => array(
        'present-address' => array(
            'zila' => 'dhaka',
            'thana' => 'khilkhet'
        ),
        'permanent-address' => array(
            'zila' => 'gazipur',
            'thana' => 'kaligonj'
        )
    ),
    'aga' => 25,
    'gender' => 'male',
    'nick' => 'netcse'
);

$decode = json_encode($json);

$decoded = json_decode($decode);

echo $json['name'];




