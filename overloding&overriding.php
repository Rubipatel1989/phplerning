<?php

// Overriding. This is used in parent child concept in inheritance muct be inherit.
class Dad {

    public function bike() {
        echo "Bike";
    }

}

class Son extends Dad {

    public function bike() {
        echo "New Bike";
    }

}

$bike = new Son();
echo $bike->bike();
echo "<br>";

