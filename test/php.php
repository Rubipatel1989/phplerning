<?php

$mainArray = [];

//print_r($array);
function printMaxValue($array) {
    for ($i = 0; $i < count($array) - 1; $i++) {       
        if (is_array($array[$i])) {
            printMaxValue($array[$i]);
        } else {
            array_push($mainArray, $array[$i]);
        }
    }
}

$array = [
    100, 95, [
        [
            60, 70, 80
        ],
        50, 40
    ]
];
echo "<pre>";
printMaxValue($array);
//print_r(max($mainArray));

//print_r($array);