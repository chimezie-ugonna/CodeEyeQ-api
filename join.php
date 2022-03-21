<?php
require "database_connection.php";
$status = array();
if($connection){
    $ciphering = "AES-128-CTR";
    $encryption_iv = (string)rand(1000000000000000,9999999999999999);
    $encryption_key = (string)rand(1000000000000000,9999999999999999);
    $encryption_iv_ = base64_encode($encryption_iv);
    $encryption_key_ = base64_encode($encryption_key);

    $user_id = addslashes($_POST["user_id"]);
    if(isset($_POST['user_id']) && $user_id != ""){
        $full_name = addslashes($_POST["full_name"]);
        $email = addslashes($_POST["email"]);
        $device_token = addslashes($_POST["device_token"]);
        $device_brand = addslashes($_POST["device_brand"]);
        $device_model = addslashes($_POST["device_model"]);
        $app_version = addslashes($_POST["app_version"]);
        $os_version = addslashes($_POST["os_version"]);

        if($full_name != ""){
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
        }

        if($email != ""){
            $email = openssl_encrypt($email, $ciphering, $encryption_key, 0, $encryption_iv);
        }
        $device_brand = openssl_encrypt($device_brand, $ciphering, $encryption_key, 0, $encryption_iv);
        $device_model = openssl_encrypt($device_model, $ciphering, $encryption_key, 0, $encryption_iv);
        $app_version = openssl_encrypt($app_version, $ciphering, $encryption_key, 0, $encryption_iv);
        $os_version = openssl_encrypt($os_version, $ciphering, $encryption_key, 0, $encryption_iv);

        $query = "insert into users (user_id, email, first_name, last_name, image_status, image_path, gender, dob, encryption_key, encryption_iv, theme, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now()) on conflict (user_id) do nothing";
        $statement = $pdo->prepare($query);
        $statement->execute(array($user_id, $email, $first_name, $last_name, "default", "", "", "", $encryption_key_, $encryption_iv_, "system"));

        $query = "delete from login_info where user_id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute(array($user_id));

        $query = "insert into login_info (user_id, device_token, device_brand, device_model, app_version, encryption_key, encryption_iv, os_version, done_at) values (?, ?, ?, ?, ?, ?, ?, ?, now())";
        $statement = $pdo->prepare($query);
        $statement->execute(array($user_id, $device_token, $device_brand, $device_model, $app_version, $encryption_key_, $encryption_iv_, $os_version));

        $status["response"] = "Done";
    }
}else{
    $status["response"] = "Connection failed";
}
echo json_encode($status);
$pdo = null;
?>
