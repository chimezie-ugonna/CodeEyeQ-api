<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/data_security.php";
require_once "../models/users.php";
require_once "../models/login_info.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $users = new users($connection);
    $login_info = new login_info($connection);
    $data_security = new data_security();

    if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_SERVER["CONTENT_TYPE"] == "application/json" || $_SERVER["CONTENT_TYPE"] == "multipart/form-data" || $_SERVER["CONTENT_TYPE"] == "application/x-www-form-urlencoded") {
            $user_id = "";
            $full_name = "";
            $email = "";
            $device_token = "";
            $device_brand = "";
            $device_model = "";
            $app_version = "";
            $os_version = "";
            if ($_SERVER["CONTENT_TYPE"] == "application/json") {
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                try {
                    $data = json_decode(file_get_contents("php://input"));
                    if (isset($data) && isset($data["user_id"]) && $data["user_id"] != "" && isset($data["full_name"]) && $data["full_name"] != "" && isset($data["email"]) && $data["email"] != "" && isset($data["device_token"]) && $data["device_token"] != "" && isset($data["device_brand"]) && $data["device_brand"] != "" && isset($data["device_model"]) && $data["device_model"] != "" && isset($data["app_version"]) && $data["app_version"] != "" && isset($data["os_version"]) && $data["os_version"] != "") {
                        $user_id  =  addslashes($data["user_id"]);
                        $device_token  =  addslashes($data["device_token"]);
                        $full_name  =  addslashes($data["full_name"]);
                        $email  =  addslashes($data["email"]);
                        $device_brand  =  addslashes($data["device_brand"]);
                        $device_model  =  addslashes($data["device_model"]);
                        $app_version  =  addslashes($data["app_version"]);
                        $os_version  =  addslashes($data["os_version"]);
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else if ($_SERVER["CONTENT_TYPE"] == "multipart/form-data" || $_SERVER["REQUEST_METHOD"] == "application/x-www-form-urlencoded") {
                if (isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['full_name']) && $_POST['full_name'] != "" && isset($_POST['email']) && $_POST['email'] != "" && isset($_POST['device_token']) && $_POST['device_token'] != "" && isset($_POST['device_brand']) && $_POST['device_brand'] != "" && isset($_POST['device_model']) && $_POST['device_model'] != "" && isset($_POST['app_version']) && $_POST['app_version'] != "" && isset($_POST['os_version']) && $_POST['os_version'] != "") {
                    $user_id  =  addslashes($_POST["user_id"]);
                    $device_token  =  addslashes($_POST["device_token"]);
                    $full_name  =  addslashes($_POST["full_name"]);
                    $email  =  addslashes($_POST["email"]);
                    $device_brand  =  addslashes($_POST["device_brand"]);
                    $device_model  =  addslashes($_POST["device_model"]);
                    $app_version  =  addslashes($_POST["app_version"]);
                    $os_version  =  addslashes($_POST["os_version"]);
                }
            }
            if ($user_id != "" && $full_name != "" && $email != "" && $device_token != "" && $device_brand != "" && $device_model != "" && $app_version != "" && $os_version != "") {
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
                $email = $data_security->encrypt($email);
                $device_brand = $data_security->encrypt($device_brand);
                $device_model = $data_security->encrypt($device_model);
                $app_version = $data_security->encrypt($app_version);
                $os_version = $data_security->encrypt($os_version);

                if ($users->create($user_id, $email, $first_name, $last_name, $data_security->decryption_key, $data_security->decryption_iv, $data_security->encrypt("system"))) {
                    if ($login_info->create($user_id, $device_token, $device_brand, $device_model, $app_version, $data_security->decryption_key, $data_security->decryption_iv, $os_version)) {
                        $status["response"] = "Success";
                        $status["message"] = "Account created successfully and log in successful.";
                        http_response_code(200);
                    } else {
                        $status["response"] = "Failed";
                        $status["message"] = "Account created successfully but log in failed.";
                        http_response_code(404);
                    }
                } else {
                    $status["response"] = "Failed";
                    $status["message"] = "Account creation failed.";
                    http_response_code(404);
                }
            } else {
                $status["response"] = "Failed";
                $status["message"] = "All required parameters were not found.";
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "Proper content type was not used.";
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
