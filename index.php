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
    $encryption_iv = base64_decode("ODc5MDI2ODc5NTM3NzE5MA==");
    $encryption_key = base64_decode("MTU4NTExODM5MzM1NTI1OQ==");
    echo "device_brand: " . openssl_decrypt("u5twsxNEww==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "device_model: " . openssl_decrypt("m7cwgVcYkdU=", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "app_version: " . openssl_decrypt("np9vsw9FyrOnAxXK3Q==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "time_zone: " . openssl_decrypt("iZxvqQVLi9/3SkqX", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "date: " . openssl_decrypt("hZtvow4Knb+2HxXW3w==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "time: " . openssl_decrypt("+sAv+EZr6Q==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";

} catch(Exception $e) {
    echo $e->getMessage();
}
?>
