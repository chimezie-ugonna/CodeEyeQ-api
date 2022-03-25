<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER["DOCUMENT_ROOT"] . "config/database.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "models/data_security.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "models/login_info.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "models/users.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $users = new users($connection);
    $data_security = new data_security();

    $user_id = addslashes($_POST["user_id"]);
    if (isset($_POST['user_id']) && $user_id != "") {
        $full_name = addslashes($_POST["full_name"]);
        $email = addslashes($_POST["email"]);
        $device_token = addslashes($_POST["device_token"]);
        $device_brand = addslashes($_POST["device_brand"]);
        $device_model = addslashes($_POST["device_model"]);
        $app_version = addslashes($_POST["app_version"]);
        $os_version = addslashes($_POST["os_version"]);

        if ($full_name != "") {
            $full_name_split = explode(" ", $full_name);
            if (count($full_name_split) > 0) {
                $first_name = $data_security->encrypt($full_name_split[0]);
                $ln = "";
                if (count($full_name_split) > 1) {
                    for ($i = 1; $i < count($full_name_split); $i++) {
                        if ($i == 1) {
                            $ln = $full_name_split[$i];
                        } else {
                            $ln = $ln . " " . $full_name_split[$i];
                        }
                    }
                }
                if ($ln != "") {
                    $last_name = $data_security->encrypt($ln);
                } else {
                    $last_name = "";
                }
            }
        }

        if ($email != "") {
            $email = $data_security->encrypt($email);
        }
        $device_brand = $data_security->encrypt($device_brand);
        $device_model = $data_security->encrypt($device_model);
        $app_version = $data_security->encrypt($app_version);
        $os_version = $data_security->encrypt($os_version);

        $users->insert($user_id, $email, $first_name, $last_name, "default", "", "", "", $data_security->encryption_key_, $data_security->encryption_iv_, "system");
        $login_info->delete($user_id);
        $login_info->insert($user_id, $device_token, $device_brand, $device_model, $app_version, $data_security->encryption_key_, $data_security->encryption_iv_, $os_version);

        $status["response"] = "Success";
        $status["message"] = "User created successfully.";
        http_response_code(200);
    } else {
        $status["response"] = "Failed";
        $status["message"] = "Required parameters not found.";
        http_response_code(404);
    }
} else {
    $status["response"] = "Failed";
    $status["message"] = "Database connection failed.";
    http_response_code(404);
}
echo json_encode($status);