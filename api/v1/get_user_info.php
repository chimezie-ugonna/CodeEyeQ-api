<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../models/database.php";
require_once "../models/data_security.php";
require_once "../models/users.php";
require_once "../models/response.php";
require_once "../models/authentication.php";

$database = new database();
$connection = $database->connect();
if ($connection != null) {
    $users = new users($connection);
    $data_security = new data_security();
    $response = new response();
    $authentication = new authentication();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            list($type, $token) = explode(" ", $_SERVER["HTTP_AUTHORIZATION"], 2);
            if (strcasecmp($type, "Bearer") == 0) {
                $data = $authentication->decode($token);
                if ($data != false) {
                    $user_id = $data["user_id"];
                    $statement = $users->read($user_id);
                    if ($statement != null) {
                        if ($statement->rowCount() > 0) {
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                $decryption_key = $row["decryption_key"];
                                $decryption_iv = $row["decryption_iv"];
                                $data = array();

                                foreach ($row as $key => $value) {
                                    if ($key == "user_id" || $key == "created_at" || $key == "decryption_key" || $key == "decryption_iv") {
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
