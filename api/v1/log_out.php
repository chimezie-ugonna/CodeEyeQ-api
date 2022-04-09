<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/login_info.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $user_id = addslashes($_GET["user_id"]);

            if ($login_info->delete($user_id)) {
                $status["response"] = "Success";
                $status["message"] = "Logged out successfully.";
                http_response_code(200);
            } else {
                $status["response"] = "Failed";
                $status["message"] = "Log out failed.";
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "A required parameter was not found.";
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
