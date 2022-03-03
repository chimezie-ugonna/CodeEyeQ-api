<?php
require "database_connection.php";
$status = array();
if($connection){
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    try {
        $user_id = generate_user_id($pdo);
        echo "User Id" . $user_id;

    } catch(Exception $e) {
        echo $e->getMessage();
    }
    /*$ciphering = "AES-128-CTR";
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

    $user_id = generate_user_id();

    $password = openssl_encrypt("1234abcd", $ciphering, $encryption_key, 0, $encryption_iv);
    $time_zone = "Africa/Lagos";
    date_default_timezone_set($time_zone);
    $date = openssl_encrypt(date("F j, Y"), $ciphering, $encryption_key, 0, $encryption_iv);
    $time = openssl_encrypt(date("g:i A"), $ciphering, $encryption_key, 0, $encryption_iv);

    $query = "insert into users (user_id, email, first_name, last_name, password, image_status, image_path, gender, dob, encryption_key, encryption_iv, date_created, time_created, time_zone) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute(array($user_id, $email, $first_name, $last_name, $password, "default", "", "", "", $encryption_key_, $encryption_iv_, $date, $time, $time_zone));*/

    $status["response"] = "Done";
}else{
    $status["response"] = "Connection failed";
}
echo json_encode($status);
$pdo = null;

function generate_user_id($pdo){

    $user_id = "";
    $alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $num = array();
    for ($i = 0; $i < 12; $i++) {
        $num[$i] = $alphabets[rand(0,25)] . (string)rand(0,9);
    }
    for ($i = 0; $i < count($num); $i++) {
        $user_id = $user_id . $num[$i];
    }

    $user_id = "M1Y2M6V7G6I0K4Q2V6G4E7T5";
    $query = "select * from users where user_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($user_id));
    $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
    if($result > 0){
        echo "user id used before </br>";
        generate_user_id($pdo);
    }else{
        return $user_id;
    }
}
?>
