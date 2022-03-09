<?php
require "database_connection.php";
$status = array();
if($connection){
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    try {
        $ciphering = "AES-128-CTR";
        $encryption_iv = (string)rand(1000000000000000,9999999999999999);
        $encryption_key = (string)rand(1000000000000000,9999999999999999);
        $encryption_iv_ = base64_encode($encryption_iv);
        $encryption_key_ = base64_encode($encryption_key);

        $user_id = addslashes($_POST["user_id"]);
        $device_token = addslashes($_POST["device_token"]);
        $device_brand = addslashes($_POST["device_brand"]);
        $device_model = addslashes($_POST["device_model"]);
        $app_version = addslashes($_POST["app_version"]);
        $time_zone = addslashes($_POST["time_zone"]);
        date_default_timezone_set($time_zone);
        $date = date("F j, Y");
        $time = date("g:i A");
        /*$date = openssl_encrypt(date("F j, Y"), $ciphering, $encryption_key, 0, $encryption_iv);
        $time = openssl_encrypt(date("g:i A"), $ciphering, $encryption_key, 0, $encryption_iv);

        $device_brand = openssl_encrypt($device_brand, $ciphering, $encryption_key, 0, $encryption_iv);
        $device_model = openssl_encrypt($device_model, $ciphering, $encryption_key, 0, $encryption_iv);
        $app_version = openssl_encrypt($app_version, $ciphering, $encryption_key, 0, $encryption_iv);
        $time_zone = openssl_encrypt($time_zone, $ciphering, $encryption_key, 0, $encryption_iv);*/

        $query = "delete from login_info where user_id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute(array($user_id));

        $query = "insert into login_info (user_id, device_token, device_brand, device_model, app_version, date, time, time_zone, encryption_key, encryption_iv) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $pdo->prepare($query);
        $statement->execute(array($user_id, $device_token, $device_brand, $device_model, $app_version, $date, $time, $time_zone, $encryption_key_, $encryption_iv_));

        $status["response"] = "Done";
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}else{
    $status["response"] = "Connection failed";
}
echo json_encode($status);
$pdo = null;
?>
