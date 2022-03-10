<?php
require "database_connection.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    /*$query = "select * from login_info where date = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array("hZtvow4Knb+2HxXW3w=="));
    $result = $statement->fetch_assoc();
    while ($row = $result) {
        echo $row['time'];
        break;
    }*/

    $ciphering = "AES-128-CTR";
    $encryption_iv = base64_decode("NzQwNDAxMjY2ODgzMzczNw==");
    $encryption_key = base64_decode("MTczNTg2ODg4MDMyNDM0Nw==");
    echo "device_brand: " . openssl_decrypt("AC9y7xr0lg==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "device_model: " . openssl_decrypt("IAMy3V6oxCw=", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "app_version: " . openssl_decrypt("JStt7wb1n0qSb00s7A==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "time_zone: " . openssl_decrypt("Miht9Qz73ibCJhJx", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "date: " . openssl_decrypt("Pi9t/we6wFqPYU8y7l4=", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "time: " . openssl_decrypt("Qnwlr1e6sCc=", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";

} catch(Exception $e) {
    echo $e->getMessage();
}
?>
