<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/data_security.php";
require_once "../models/login_info.php";
require_once "../models/response.php";

$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $login_info = new login_info($connection);
    $data_security = new data_security();
    $response = new response();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $user_id = addslashes($_GET["user_id"]);

            $statement = $login_info->read($user_id);
            if ($statement != null) {
                if ($statement->rowCount() > 0) {
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $decryption_key = $row["decryption_key"];
                        $decryption_iv = $row["decryption_iv"];
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

                        $response->send(200, "Data was found.", $data);
                        break;
                    }
                } else {
                    $response->send(404, "Data not found.");
                }
            } else {
                $response->send(500, "Request operation failed.");
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
