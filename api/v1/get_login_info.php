<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
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

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $user_id = addslashes($_GET["user_id"]);

            $statement = $login_info->read($user_id);
            if ($statement != null) {
                if ($statement->rowCount() > 0) {
                    $status["response"] = "Success";
                    $status["message"] = "Data was found.";
                    $status["data"] = array();

                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        array_push($status["data"], $row);
                        break;
                    }
                    http_response_code(200);
                } else {
                    $status["response"] = "Failed";
                    $status["message"] = "No data found.";
                    http_response_code(404);
                }
            } else {
                $status["response"] = "Failed";
                $status["message"] = "Request operation failed.";
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "Required parameter was not found.";
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
