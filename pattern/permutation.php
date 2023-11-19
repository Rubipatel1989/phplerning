<?php

$string = "STACK";

Function getPermutaion($arg) {
    $array = is_string($arg) ? str_split($arg) : $arg;

    if (1 == count($array)) {
        return $array;
    }
    $result = $array;

    foreach ($array as $key => $items) {
        foreach (getPermutaion(array_diff_key($array, array($key => $items))) as $out) {
            $result[] = $items . $out;
        }
    }
    Return $result;
}

echo "<pre>";
print_r(getPermutaion($string));
