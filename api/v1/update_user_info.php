<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../models/database.php";
require_once "../models/data_security.php";
require_once "../models/users.php";
require_once "../models/response.php";
require_once "../models/authentication.php";
require_once "../../vendor/autoload.php";

$database = new database();
$connection = $database->connect();
$response = new response();
if ($connection != null) {
    $users = new users($connection);
    $data_security = new data_security();
    $authentication = new authentication();

    if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            list($type, $token) = explode(" ", $_SERVER["HTTP_AUTHORIZATION"], 2);
            if (strcasecmp($type, "Bearer") == 0) {
                $data = $authentication->decode($token);
                if ($data != false && isset($data["user_id"])) {
                    $user_id = $data["user_id"];
                    $statement = $users->read($user_id);
                    if ($statement != null) {
                        if ($statement->rowCount() > 0) {
                            $full_name = "";
                            $email = "";
                            $theme = "";
                            $image = "";
                            $gender = "";
                            $dob = "";
                            $type = "";
                            $point = "";
                            if ($_SERVER["CONTENT_TYPE"] == "application/json") {
                                $data = json_decode(file_get_contents("php://input"), true);
                                if (isset($data['full_name']) && $data['full_name'] != "") {
                                    $full_name  =  addslashes($data["full_name"]);
                                }
                                if (isset($data['email']) && $data['email'] != "") {
                                    $email  =  addslashes($data["email"]);
                                }
                                if (isset($data['theme']) && $data['theme'] != "") {
                                    $theme  =  addslashes($data["theme"]);
                                }
                                if (isset($data['gender']) && $data['gender'] != "") {
                                    $gender  =  addslashes($data["gender"]);
                                }
                                if (isset($data['image']) && $data['image'] != "") {
                                    $image  =  addslashes($data["image"]);
                                }
                                if (isset($data['dob']) && $data['dob'] != "") {
                                    $dob  =  addslashes($data["dob"]);
                                }
                                if (isset($data['type']) && $data['type'] != "") {
                                    $type  =  addslashes($data["type"]);
                                }
                                if (isset($data['point']) && $data['point'] != "") {
                                    $point  =  addslashes($data["point"]);
                                }
                            } else {
                                if (isset($_POST['full_name']) && $_POST['full_name'] != "") {
                                    $full_name  =  addslashes($_POST["full_name"]);
                                }
                                if (isset($_POST['email']) && $_POST['email'] != "") {
                                    $email  =  addslashes($_POST["email"]);
                                }
                                if (isset($_POST['theme']) && $_POST['theme'] != "") {
                                    $theme  =  addslashes($_POST["theme"]);
                                }
                                if (isset($_POST['gender']) && $_POST['gender'] != "") {
                                    $gender  =  addslashes($_POST["gender"]);
                                }
                                if (isset($_POST['image']) && $_POST['image'] != "") {
                                    $image  =  addslashes($_POST["image"]);
                                }
                                if (isset($_POST['dob']) && $_POST['dob'] != "") {
                                    $dob  =  addslashes($_POST["dob"]);
                                }
                                if (isset($_POST['type']) && $_POST['type'] != "") {
                                    $type  =  addslashes($_POST["type"]);
                                }
                                if (isset($_POST['point']) && $_POST['point'] != "") {
                                    $point  =  addslashes($_POST["point"]);
                                }
                            }

                            if ($full_name != "" || $email != "" || $theme != "" || $gender != "" || $image != "" || $dob != "" || $type != "" || $point != "") {
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                    $data_security->decryption_key = $row["decryption_key"];
                                    $data_security->decryption_iv = $row["decryption_iv"];
                                    $data_security->encryption_key = base64_decode($row["decryption_key"]);
                                    $data_security->encryption_iv = base64_decode($row["decryption_iv"]);

                                    if ($full_name != "") {
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
                                    } else {
                                        $first_name = $row["first_name"];
                                        $last_name = $row["last_name"];
                                    }

                                    if ($email != "") {
                                        $email = $data_security->encrypt($email);
                                    } else {
                                        $email = $row["email"];
                                    }

                                    if ($theme != "") {
                                        $theme = $data_security->encrypt($theme);
                                    } else {
                                        $theme = $row["theme"];
                                    }

                                    if ($gender != "") {
                                        $gender = $data_security->encrypt($gender);
                                    } else {
                                        $gender = $row["gender"];
                                    }

                                    $image_path = $row["image_path"];
                                    if ($image != "") {
                                        if ($image_path == "") {
                                            $random = rand(100000000, 999999999);
                                            $image_path = $data_security->encrypt("IMG_" . (string)$random);
                                        }
                                        /*require 'autoload.php';
                                            require 'src/Helpers.php';
                                            require 'settings.php';
                                            $cloudinary_response = \Cloudinary\Uploader::upload("data:image/png;base64," . $image, array("folder" => "codeeyeq_images", "public_id" => $image_path, "overwrite" => true));
                                            if ($cloudinary_response) {
                                                foreach ($cloudinary_response as $cloudinary_key => $cloudinary_value) {
                                                    if ($cloudinary_key == "version") {
                                                        $image_path .= "_" . $cloudinary_value;
                                                    }
                                                    if ($cloudinary_key == "format") {
                                                        $image_path .= "_" . $cloudinary_value;
                                                    }
                                                }
                                            }*/
                                    }

                                    if ($dob != "") {
                                        $dob = $data_security->encrypt($dob);
                                    } else {
                                        $dob = $row["dob"];
                                    }

                                    if ($type != "") {
                                        $type = $data_security->encrypt($type);
                                    } else {
                                        $type = $row["type"];
                                    }

                                    if ($point != "") {
                                        $point = $data_security->encrypt($point);
                                    } else {
                                        $point = $row["point"];
                                    }

                                    if ($users->update($user_id, $email, $first_name, $last_name, $image_path, $gender, $dob, $theme, $type, $point)) {
                                        $response->send(200, "Account updated successfully.");
                                    } else {
                                        $response->send(500, "Account updation failed.");
                                    }
                                    break;
                                }
                            } else {
                                $response->send(400, "There is nothing to update.");
                            }
                        } else {
                            $response->send(401, "User does not exist.");
                        }
                    } else {
                        $response->send(500, "Authentication failed.");
                    }
                } else {
                    $response->send(401, "Unauthorized access, bearer token is invalid.");
                }
            } else {
                $response->send(401, "Unauthorized access, bearer token is required.");
            }
        } else {
            $response->send(401, "Unauthorized access, bearer token is required.");
        }
    } else {
        $response->send(400, "Proper request method was not used.");
    }
} else {
    $response->send(500, "Database connection failed.");
}
