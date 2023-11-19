<?php

// Overloding
interface Shape {

    public function calcArea();
}

class Circle implements Shape {

    private $radius;

    public function __construct($radius) {
        $this->radius = $radius;
    }

    public function calcArea() {
        return $this->radius * $this->radius * pi();
    }

}

class Rectanglee implements Shape {

    private $width;
    private $height;

    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function calcArea() {
        return $this->width * $this->height;
    }

}

$test1 = new Circle(3.4);
echo "Circle Area : " . $test1->calcArea();
echo "<br>";
$test2 = new Rectanglee(12, 13);
echo "Rectanglee Area : " . $test2->calcArea();
