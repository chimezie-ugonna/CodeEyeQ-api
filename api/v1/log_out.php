<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/login_info.php";
require_once "../models/response.php";

$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $response = new response();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $user_id = addslashes($_GET["user_id"]);

            if ($login_info->delete($user_id)) {
                $response->send(200, "Log out successful.");
            } else {
                $response->send(500, "Log out failed.");
            }
        } else {
            $response->send(400, "A required parameter was not found.");
        }
    } else {
        $response->send(400, "Proper request method was not used.");
    }
} else {
    $response->send(500, "Database connection failed.");
}
