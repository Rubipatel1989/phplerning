<?php

class Singleton {

    private static $instance = null;
    private $dbconn;

    private function __construct() {
        echo "DB Connected" . "<br>";
    }

    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new Static();
        } else {
            echo "Already Connected." . "<br>";
        }
        return self::$instance;
    }

    public static function getDbConn() {
        try {
            $db = self::$instance;
            $db->dbconn = new PDO('mysql:host=localhost;dbname=rental_software', 'root', '');
            $db->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db->dbconn;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}

//$obj = new Singleton();
//$connection = $obj->getInstance();
$obj = Singleton::getInstance();
$pdo = Singleton::getDbConn();

$statement = $pdo->prepare("select state_id as id, state_name as name from state
            where is_active = 'Y' order by state_name ");
$statement->execute();
$result = $statement->fetchAll();
$statement->closeCursor();
echo "<pre>";
print_r($result);
