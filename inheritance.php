<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Employee {

    public $name;
    public $age;
    public $salary;

    function __construct($n, $a, $s) {
        $this->name = $n;
        $this->age = $a;
        $this->salary = $s;
    }

    function info() {
        echo "<h2>Employee Profile</h2>" . "<br>";
        echo "<h3>Employee Name</h3>" . $this->name . "<br>";
        echo "<h3>Employee Age</h3>" . $this->age . "<br>";
        echo "<h3>Employee Salary</h3>" . $this->salary . " INR<br>";
    }

}

class Manager extends Employee {
    public $ta = 1000;
    public $phone = 500;
    public $totalSalary;
    function info() {
        $this->totalSalary = $this->salary + $this->ta + $this->phone;
        echo "<h2>Manager Profile</h2>" . "<br>";
        echo "<h3>Manager Name</h3>" . $this->name . "<br>";
        echo "<h3>Manager Age</h3>" . $this->age . "<br>";
        echo "<h3>Manager Salary</h3>" . $this->salary . " INR<br>";
        echo "<h3>Manager Total Salary</h3>" . $this->totalSalary . " INR<br>";
    }
}

$e1 = new Manager("Pawan Kumar", 32, 5000);
$e2 = new Employee("Pawan Kumar", 32, 5000);
$e1->info();
$e2->info();
echo "<pre>";
print_r($e1);
print_r($e2);
