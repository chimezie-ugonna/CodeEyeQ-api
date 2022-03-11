<?php
require "database_connection.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    /*$query = "select * from login_info where user_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array("fbD3WGeem7cmZBUmSU94LQy7Cn52"));
    $result = $statement->fetch_assoc();
    while ($row = $result) {
        echo $row['time'];
        break;
    }*/

} catch(Exception $e) {
    echo $e->getMessage();
}
?>
