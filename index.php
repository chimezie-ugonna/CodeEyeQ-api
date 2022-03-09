<?php
require "database_connection.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    $query = "select * from login_info where date = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array("hZtvow4Knb+2HxXW3w=="));
    $result = $statement->fetch_assoc();
    while ($row = $result) {
        echo $row['time'];
        break;
    }
} catch(Exception $e) {
    echo $e->getMessage();
}
?>
