<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";
require_once "../models/data_security.php";
require_once "../models/users.php";

$status = array();
$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $users = new users($connection);
    $data_security = new data_security();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $user_id = addslashes($_GET["user_id"]);

            $statement = $users->read($user_id);
            if ($statement != null) {
                if ($statement->rowCount() > 0) {
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $statement = $users->read_column_names();
                        if ($statement != null) {
                            if ($statement->rowCount() > 0) {
                                $status["response"] = "Success";
                                $status["message"] = "Data was found.";
                                $status["data"] = array();

                                $encryption_key = $row["encryption_key"];
                                $encryption_iv = $row["encryption_iv"];
                                $count = 0;
                                while ($row2 = $statement->fetch(PDO::FETCH_ASSOC)) {
                                    if ($row2[$count] == "user_id") {
                                        array_push($status["data"][$row2[$count]], $row[$row2[$count]]);
                                    } else {
                                        if ($row[$row2[$count]] != "") {
                                            array_push($status["data"][$row2[$count]], $data_security->decrypt($encryption_key, $encryption_iv, $row[$row2[$count]]));
                                        } else {
                                            array_push($status["data"][$row2[$count]], $row[$row2[$count]]);
                                        }
                                    }
                                    $count++;
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
                        break;
                    }
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
