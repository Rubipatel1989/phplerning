<?php

interface parrent1 {

    function add($a, $b);
}

interface parrent2 {

    function sub($c, $d);
}

//$test = new A();
class childClass implements parrent1, parrent2 {

    public function add($a, $b) {
        echo $a + $b;
    }

    public function sub($a, $b) {
        echo $a - $b;
    }

}

$test1 = new childClass();
$test1->add(10, 11);
echo "<br>";
$test1->sub(10, 11);
