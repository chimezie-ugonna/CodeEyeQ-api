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
    $encryption_iv = base64_decode("NDM1NTI0Mjc2MjgyMTk3OQ==");
    $encryption_key = base64_decode("NzY1MTcxNzYzNzM2OTY0Ng==");
    echo "device_brand: " . openssl_decrypt("UjZzvyYjHA==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "device_model: " . openssl_decrypt("chozjWJ/TuI=", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "app_version: " . openssl_decrypt("dzJsvzoiFYQ7zG7U8A==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "time_zone: " . openssl_decrypt("YDFspTAsVOhrhTGJ", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "date: " . openssl_decrypt("bDZsrzttSpQmwmzK8oc=", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";
    echo "time: " . openssl_decrypt("EG0s9XMMNg==", $ciphering, $encryption_key, 0, $encryption_iv) . "<br/>";

} catch(Exception $e) {
    echo $e->getMessage();
}
?>
