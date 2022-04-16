<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/login_info.php";
require_once "../models/users.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $users = new users($connection);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $user_id = addslashes($_GET["user_id"]);

            if ($login_info->delete($user_id)) {
                if ($users->delete($user_id)) {
                    $status["response"] = "Success";
                    $status["message"] = "Account deleted successfully.";
                    $status["data"] = array();
                    http_response_code(200);
                } else {
                    $status["response"] = "Failed";
                    $status["message"] = "Account deletion failed.";
                    $status["data"] = array();
                    http_response_code(404);
                }
            } else {
                $status["response"] = "Failed";
                $status["message"] = "Account deletion failed.";
                $status["data"] = array();
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "A required parameter was not found.";
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
