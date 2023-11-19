<?php

##1. ########## Array arrayDigotional Sum
$arr = array(
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
);
$diagonalSum = 0;
foreach ($arr as $k => $arr1) {
    $diagonalSum += $arr1[$k];
}
echo $diagonalSum . "<br>";
## 2. Array_column

$a = array(
    array(
        'id' => 5698,
        'first_name' => 'Peter',
        'last_name' => 'Griffin',
    ),
    array(
        'id' => 4767,
        'first_name' => 'Ben',
        'last_name' => 'Smith',
    ),
    array(
        'id' => 3809,
        'first_name' => 'Joe',
        'last_name' => 'Doe',
    )
);

$last_names = array_column($a, 'last_name');
print_r($last_names);
