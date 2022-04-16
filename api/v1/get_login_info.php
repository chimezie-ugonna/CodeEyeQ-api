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
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $decryption_key = $row["decryption_key"];
                        $decryption_iv = $row["decryption_iv"];

                        $status["response"] = "Success";
                        $status["message"] = "Data was found.";
                        $status["data"] = array();
                        $data = array();

                        foreach ($row as $key => $value) {
                            if ($key == "user_id" || $key == "device_token" || $key == "done_at" || $key == "decryption_key" || $key == "decryption_iv") {
                                $data[$key] = $value;
                            } else {
                                if ($value != "") {
                                    $data[$key] = $data_security->decrypt($decryption_key, $decryption_iv, $value);
                                } else {
                                    $data[$key] = $value;
                                }
                            }
                        }

                        array_push($status["data"], $data);
                        http_response_code(200);
                        break;
                    }
                } else {
                    $status["response"] = "Failed";
                    $status["message"] = "No data found.";
                    $status["data"] = array();
                    http_response_code(404);
                }
            } else {
                $status["response"] = "Failed";
                $status["message"] = "Request operation failed.";
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
