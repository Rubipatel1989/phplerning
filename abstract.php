<?php

abstract class parrentClass
{
    public $name;

    abstract protected function calc($a, $b);
}

class childClass extends parrentClass
{
    public function calc($a, $b)
    {
        echo $a + $b;
    }

}


abstract class pawanCalculator
{
    public $name;
    abstract protected function calculator($a, $b);
}
class cal extends pawanCalculator
{
    public function calculator($a, $b)
    {
        echo $a + $b . "<br>";
    }
}

$pawan = new cal();
$pawan->calculator(34, 23);
$test = new childClass();
$test->calc(15, 16);
