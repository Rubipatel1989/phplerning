<?php

$val1 = 5;
$val2 = 6;
$max = ($val1 > $val2) ? $val1 : $val2;

while (1) {
    if ($max % $val1 == 0 && $max % $val2 == 0) {
        Echo "LCM of " . $val1 . " and " . $val2 . " have " . $max . "<br>";
    }
    $max = $max + 1;
}
Echo $max;
