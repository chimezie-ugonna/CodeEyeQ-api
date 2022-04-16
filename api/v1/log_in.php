<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/data_security.php";
require_once "../models/login_info.php";
require_once "../models/users.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $users = new users($connection);
    $data_security = new data_security();

    if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = "";
        $device_token = "";
        $device_brand = "";
        $device_model = "";
        $app_version = "";
        $os_version = "";
        if ($_SERVER["CONTENT_TYPE"] == "application/json") {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data) && isset($data["user_id"]) && $data["user_id"] != "" && isset($data["device_token"]) && $data["device_token"] != "" && isset($data["device_brand"]) && $data["device_brand"] != "" && isset($data["device_model"]) && $data["device_model"] != "" && isset($data["app_version"]) && $data["app_version"] != "" && isset($data["os_version"]) && $data["os_version"] != "") {
                $user_id  =  addslashes($data["user_id"]);
                $device_token  =  addslashes($data["device_token"]);
                $device_brand  =  addslashes($data["device_brand"]);
                $device_model  =  addslashes($data["device_model"]);
                $app_version  =  addslashes($data["app_version"]);
                $os_version  =  addslashes($data["os_version"]);
            }
        } else {
            if (isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['device_token']) && $_POST['device_token'] != "" && isset($_POST['device_brand']) && $_POST['device_brand'] != "" && isset($_POST['device_model']) && $_POST['device_model'] != "" && isset($_POST['app_version']) && $_POST['app_version'] != "" && isset($_POST['os_version']) && $_POST['os_version'] != "") {
                $user_id  =  addslashes($_POST["user_id"]);
                $device_token  =  addslashes($_POST["device_token"]);
                $device_brand  =  addslashes($_POST["device_brand"]);
                $device_model  =  addslashes($_POST["device_model"]);
                $app_version  =  addslashes($_POST["app_version"]);
                $os_version  =  addslashes($_POST["os_version"]);
            }
        }

        if ($user_id != "" && $device_token != "" && $device_brand != "" && $device_model != "" && $app_version != "" && $os_version != "") {
            $device_brand = $data_security->encrypt($device_brand);
            $device_model = $data_security->encrypt($device_model);
            $app_version = $data_security->encrypt($app_version);
            $os_version = $data_security->encrypt($os_version);

            if ($users->read($user_id)->rowCount() > 0) {
                if ($login_info->delete($user_id)) {
                    if ($login_info->create($user_id, $device_token, $device_brand, $device_model, $app_version, $data_security->decryption_key, $data_security->decryption_iv, $os_version)) {
                        $status["response"] = "Success";
                        $status["message"] = "Log in successful.";
                        $status["data"] = array();
                        http_response_code(200);
                    } else {
                        $status["response"] = "Failed";
                        $status["message"] = "Log in failed.";
                        $status["data"] = array();
                        http_response_code(404);
                    }
                } else {
                    $status["response"] = "Failed";
                    $status["message"] = "Log in failed.";
                    $status["data"] = array();
                    http_response_code(404);
                }
            } else {
                $status["response"] = "Failed";
                $status["message"] = "Log in failed because user does not exist.";
                $status["data"] = array();
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "All required parameters were not found.";
            $status["data"] = array();
            http_response_code(404);
        }
    } else {
        $status["response"] = "Failed";
        $status["message"] = "Proper request method was not used.";
        $status["data"] = array();
        http_response_code(404);
    }
} else {
    $status["response"] = "Failed";
    $status["message"] = "Database connection failed.";
    $status["data"] = array();
    http_response_code(404);
}
echo json_encode($status);
