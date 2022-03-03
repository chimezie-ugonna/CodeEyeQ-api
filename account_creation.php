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
    $full_name = addslashes($_POST["full_name"]);
    $full_name_split = explode(" ", $full_name);
    if(count($full_name_split) > 1){
        $first_name = openssl_encrypt($full_name_split[0], $ciphering, $encryption_key, 0, $encryption_iv);
        $last_name = openssl_encrypt($full_name_split[1], $ciphering, $encryption_key, 0, $encryption_iv);
    }else{
        $first_name = "";
        $last_name = "";
    }
    $email = openssl_encrypt(addslashes($_POST["email"]), $ciphering, $encryption_key, 0, $encryption_iv);

    $alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $code = array();
    for ($i = 0; $i < 3; $i++) {
        $code[$i] = $alphabets[rand(0,25)] . (string)rand(0,9);
    }
    $user_id = $code[0] . $code[1] . $code[2];

    $password = openssl_encrypt(addslashes($_POST["password"]), $ciphering, $encryption_key, 0, $encryption_iv);
    $date = openssl_encrypt(date("F j, Y"), $ciphering, $encryption_key, 0, $encryption_iv);
    $time = openssl_encrypt(date("g:i A"), $ciphering, $encryption_key, 0, $encryption_iv);

    $sql = "insert into users (user_id, email, first_name, last_name, password, image_status, image_path, gender, dob, date_created, time_created) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($sql);
    $statement->execute(array($user_id, $email, $first_name, $last_name, $password, "default", "", "", "", $date, $time));

    $result = $statement->fetchAll();
}else{
    $status["response"] = "connection failed";
}
echo json_encode($status);
?>
