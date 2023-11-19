<?php

class Class1 {

    private $num;

    public function __construct() {
        $this->num = 2;
        //echo $this->num;
    }

    public function getNum() {
        return $this->num;
    }

}

$test1 = new Class1();
//$test1->num = 3;
echo $test1->getNum();

