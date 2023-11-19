<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class calculation {

    public $a, $b, $c;

    public function sum() {
        $this->c = $this->a + $this->b;
        return $this->c;
    }

    public function subtract() {
        $this->c = $this->a - $this->b;
        return $this->c;
    }

}

$c1 = new calculation();
$c1->a = 20;
$c1->b = 15;
echo $c1->sum() . " Add<br>";
echo $c1->subtract() . " Subtract<br>";
