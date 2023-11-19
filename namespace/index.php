<?php
require './product.php';
require './testing.php';
 function wow() {
        echo "Wow from Testing files<br>";
    }
$obj1 = new testing\Product();
$obj2 = new product\Product();
wow();
$obj2->wow();
