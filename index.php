<?php
require "database_connection.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    $query = "select * from login_info where date = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array("hZtvow4Knb+2HxXW3w=="));
    $result = $statement->get_result();
    while ($row = $result->fetch_assoc()) {
        echo $row['time'];
    }
} catch(Exception $e) {
    echo $e->getMessage();
}
?>
