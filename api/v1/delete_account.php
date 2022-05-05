<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../models/database.php";
require_once "../models/login_info.php";
require_once "../models/users.php";
require_once "../models/response.php";
require_once "../models/authentication.php";

$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $users = new users($connection);
    $response = new response();
    $authentication = new authentication();

    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            list($type, $token) = explode(" ", $_SERVER["HTTP_AUTHORIZATION"], 2);
            if (strcasecmp($type, "Bearer") == 0) {
                $data = $authentication->decode($token);
                if ($data != false) {
                    $user_id = $data["user_id"];
                    $statement = $users->read($user_id);
                    if ($statement != null) {
                        if ($statement->rowCount() > 0) {
                            if ($login_info->delete($user_id)) {
                                if ($users->delete($user_id)) {
                                    $response->send(200, "Account deleted successfully.");
                                } else {
                                    $response->send(500, "Account deletion failed.");
                                }
                            } else {
                                $response->send(500, "Account deletion failed.");
                            }
                        } else {
                            $response->send(401, "Unauthorized access, user does not exist.");
                        }
                    } else {
                        $response->send(500, "Authentication failed.");
                    }
                } else {
                    $response->send(401, "Bearer token is invalid.");
                }
            } else {
                $response->send(401, "Bearer token is required.");
            }
        } else {
            $response->send(401, "Unauthorized access, token is required.");
        }
    } else {
        $response->send(400, "Proper request method was not used.");
    }
} else {
    $response->send(500, "Database connection failed.");
}
