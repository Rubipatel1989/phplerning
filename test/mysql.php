<?php

include '../singleton.php';

class DbRecord {

    public function getSingleData($query, $array = null) {
        try {
            if (strlen($query) > 20) {
                $pdo = Singleton::getDbConn();
                $statement = $pdo->prepare($query);
                $statement->execute($array);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();
            } else {
                $result = "Query not in proper  format.";
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }

    public function getMultipleData($query, $array = null) {
        try {
            if (strlen($query) > 20) {
                $pdo = Singleton::getDbConn();
                $statement = $pdo->prepare($query);
                $statement->execute($array);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();
            } else {
                $result = "Query not in proper  format.";
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }

    public function InsertData($query, $array = null) {
        try {
            if (strlen($query) > 20) {
                $pdo = Singleton::getDbConn();
                $statement = $pdo->prepare($query);
                $statement->execute($array);
                $result = $pdo->lastInsertId();
                $statement->closeCursor();
            } else {
                $result = "Query not in proper  format.";
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }

    public function UpdateDelete($query, $array = null) {
        try {
            if (strlen($query) > 20) {
                $pdo = Singleton::getDbConn();
                $statement = $pdo->prepare($query);
                $result = $statement->execute($array);
                $statement->closeCursor();
            } else {
                $result = "Query not in proper  format.";
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }

}

$obj = new DbRecord();

$query = "select * from users where sponsor_d = :id order by email ";
$array = [
    'id' => 3
];

$result = $obj->getSingleData($query, $array);
echo "<pre>";
print_r($result);
