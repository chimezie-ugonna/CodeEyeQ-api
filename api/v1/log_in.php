<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/data_security.php";
require_once "../models/login_info.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $data_security = new data_security();

    if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['device_token']) && $_POST['device_token'] != "" && isset($_POST['device_brand']) && $_POST['device_brand'] != "" && isset($_POST['device_model']) && $_POST['device_model'] != "" && isset($_POST['app_version']) && $_POST['app_version'] != "" && isset($_POST['os_version']) && $_POST['os_version'] != "") {
            $user_id = addslashes($_POST["user_id"]);
            $device_token = addslashes($_POST["device_token"]);
            $device_brand = $data_security->encrypt(addslashes($_POST["device_brand"]));
            $device_model = $data_security->encrypt(addslashes($_POST["device_model"]));
            $app_version = $data_security->encrypt(addslashes($_POST["app_version"]));
            $os_version = $data_security->encrypt(addslashes($_POST["os_version"]));

            if ($login_info->insert($user_id, $device_token, $device_brand, $device_model, $app_version, $data_security->encryption_key_, $data_security->encryption_iv_, $os_version)) {
                $status["response"] = "Success";
                $status["message"] = "Logged in successfully.";
                http_response_code(200);
            } else {
                $status["response"] = "Failed";
                $status["message"] = "Log in failed.";
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "All required parameters were not found.";
            http_response_code(404);
        }
    } else {
        $status["response"] = "Failed";
        $status["message"] = "Proper request method was not used.";
        http_response_code(404);
    }
} else {
    $status["response"] = "Failed";
    $status["message"] = "Database connection failed.";
    http_response_code(404);
}
echo json_encode($status);
