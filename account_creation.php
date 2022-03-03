<?php
require "database_connection.php";
date_default_timezone_set('Africa/Lagos');
$status = array();
if($connection){
    $ciphering = "AES-128-CTR";
    $encryption_iv = (string)rand(1000000000000000,9999999999999999);
    $encryption_key = (string)rand(1000000000000000,9999999999999999);
    $encryption_iv_ = base64_encode($encryption_iv);
    $encryption_key_ = base64_encode($encryption_key);

    $account_name_encryption = openssl_encrypt($account_name, $ciphering, $ed_key, 0, $ed_iv);
    $full_name = "Ugonna Chimezie Collins Junior";
    $full_name_split = explode(" ", $full_name);
    if(count($full_name_split) > 0){
        $first_name = openssl_encrypt($full_name_split[0], $ciphering, $encryption_key, 0, $encryption_iv);
        $ln = "";
        if(count($full_name_split) > 1){
            for($i = 1; $i < count($full_name_split); $i++){
                if($i == 1){
                    $ln = $full_name_split[$i];
                }else{
                    $ln = $ln . " " . $full_name_split[$i];
                }
            }
        }
        if($ln != ""){
            $last_name = openssl_encrypt($ln, $ciphering, $encryption_key, 0, $encryption_iv);
        }else{
            $last_name = "";
        }
    }
    $email = openssl_encrypt("ugiezie@gmail.com", $ciphering, $encryption_key, 0, $encryption_iv);

    $user_id = "";
    $alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $num = array();
    for ($i = 0; $i < 12; $i++) {
        $num[$i] = $alphabets[rand(0,25)] . (string)rand(0,9);
    }
    for ($i = 0; $i < count($num); $i++) {
        $user_id = $user_id . $num[$i];
    }

    $password = openssl_encrypt("1234abcd", $ciphering, $encryption_key, 0, $encryption_iv);
    $date = openssl_encrypt(date("F j, Y"), $ciphering, $encryption_key, 0, $encryption_iv);
    $time = openssl_encrypt(date("g:i A"), $ciphering, $encryption_key, 0, $encryption_iv);

    $sql = "insert into users (user_id, email, first_name, last_name, password, image_status, image_path, gender, dob, encryption_key, encryption_iv, date_created, time_created) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($sql);
    $statement->execute(array($user_id, $email, $first_name, $last_name, $password, "default", "", "", "", $encryption_key_, $encryption_iv_, $date, "; delete from users"));

    $result = $statement->fetchAll();
}else{
    $status["response"] = "connection failed";
}
echo json_encode($status);
?>
