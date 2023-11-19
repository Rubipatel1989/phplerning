<?php

$string = "abcdefgh";
$len = strlen($string);
for($i = $len - 1;$i >= 0; $i--) {
    echo $string[$i];
}
