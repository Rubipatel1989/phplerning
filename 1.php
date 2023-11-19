<?php

$array1 = [
    [
        "id" => 5,
        "date" => "01/05/2015",
        [
            "id" => 7,
            "date" => "05/01/2015",
        ],
        [
            "id" => 8,
            "date" => "12/12/2015",
        ],
    ]
];

$array2 = array_column($array1, "date");
$temp = 0;
for ($i = 0; $i < count($array2); $i++) {
    for ($j = 1; $j < count($array2); $j++) {
        if (strtotime($array2[$i] > strtotime($array2[$i]))) {
            $temp = $array2[$i];
            $array2[$i] = $array2[$j];
            $array2[$j] = $temp;
        }
    }
}
print_r($array2);

$category = "Supplier Development";
$string = "Supplier";
if (strpos($category, $string) !== false) {
    echo "Found!";
} else {
    echo "Not Found";
}


$data = array(4, 5, 23, 0, 100, 5, 7, 33, 44, 100);
$val = 1;
for ($i = count($data); $i >= 1; --$i) {
    for ($j = 0; $j < $i; ++$j) {
        if(isset($data[$j]) && !empty($data[$j])) {
            if ($data[$j] === $data[$i]) {
                $val = $data[$j];
            }
        }
    }
}
echo "<pre>";
print_r($data);