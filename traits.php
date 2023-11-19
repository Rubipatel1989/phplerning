<?php

trait hello {

    public function sayHello() {
        echo "I am Fine";
    }

}

class base1 {

    use hello;
}

class base2 {

    use hello;
}

$test1 = new base1();
$test2 = new base2();
$test1->sayHello();
echo "<br>";
$test2->sayHello();

const data = [
    [1, 3, 5, 7, 3],
    [1, 3, "5", 2, 4],
    ["hi", 9, 2, 6, 8]
];
