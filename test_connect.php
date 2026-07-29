<?php

require_once 'config/config.php';

// load the database class
require_once 'classes/database.php';

try{
    $pdo = Database::getConnection();
    echo "Data Connected Successfully!<br><br>";

    //show current database
    $stmt = $pdo->query("SELECT DATABASE() AS current_database");
    $result = $stmt->fetch();
    echo "Database: ". $result['current_database'] . "<br><br>";

    //show tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database: <br>";
    foreach($tables as $table){
        echo " - ". $table . "<br>";
    }
}catch(Exception $e){
    echo " -  Error". $e->getMessage(); 
}

$stmt = $pdo->query("SELECT * FROM students WHERE gpa > 3.0");
$rows = $stmt->fetchAll();
foreach($rows as $row){
    echo $row['first_name']. " ". $row['last_name']. " ". $row['gpa'] . "<br>";
}



?>