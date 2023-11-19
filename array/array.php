<?php

$a = array(1, 2, 3, 4, 5, 6, 7, 89, 9);
$res = 0;

foreach ($a as $v) {
    if ($res < $v)
        $res = $v;
}
echo $res;
echo "<br>";
$Z = [1, 1, 1, 2, 3, 4, 4, 4, 5, 6, 7, 7, 7, 8, 8, 8, 9, 10];

$new_array = array();

for ($i = 0; $i <= count($Z) - 1; $i++) {
    $count = 0;
    for ($j = 0; $j < count($Z) - 1; $j++) {
        if ($Z[$i] === $Z[$j]) {
            $count++;
        }
    }
    if ($count > 1)
        $new_array[] = $Z[$i];
}
echo"<pre>";
print_r($new_array);
