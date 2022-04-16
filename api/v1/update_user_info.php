<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
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

    if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_SERVER["CONTENT_TYPE"] == "application/json" || $_SERVER["CONTENT_TYPE"] == "multipart/form-data" || $_SERVER["CONTENT_TYPE"] == "application/x-www-form-urlencoded") {
            $user_id = "";
            $full_name = "";
            $email = "";
            $theme = "";
            $image = "";
            $gender = "";
            $dob = "";
            if ($_SERVER["CONTENT_TYPE"] == "application/json") {
                $data = json_decode(file_get_contents("php://input"));
                if (isset($data) && isset($data["user_id"]) && $data["user_id"] != "") {
                    $user_id  =  addslashes($data["user_id"]);
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
                }
            } else if ($_SERVER["CONTENT_TYPE"] == "multipart/form-data" || $_SERVER["REQUEST_METHOD"] == "application/x-www-form-urlencoded") {
                if (isset($_POST['user_id']) && $_POST['user_id'] != "") {
                    $user_id  =  addslashes($_POST["user_id"]);
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
                }
            }

            if ($user_id != "") {
                if ($full_name != "" || $email != "" || $theme != "" || $gender != "" || $image != "" || $dob != "") {
                    $statement = $users->read($user_id);
                    if ($statement != null) {
                        if ($statement->rowCount() > 0) {
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

                                if ($dob != "") {
                                    $dob = $data_security->encrypt($dob);
                                } else {
                                    $dob = $row["dob"];
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

                                if ($users->update($user_id, $email, $first_name, $last_name, $image_path, $gender, $dob, $theme)) {
                                    $status["response"] = "Success";
                                    $status["message"] = "Account updated successfully.";
                                    http_response_code(200);
                                } else {
                                    $status["response"] = "Failed";
                                    $status["message"] = "Account updation failed.";
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
                    $status["message"] = "There is nothing to update.";
                    http_response_code(404);
                }
            } else {
                $status["response"] = "Failed";
                $status["message"] = "A required parameter was not found.";
                http_response_code(404);
            }
        } else {
            $status["response"] = "Failed";
            $status["message"] = "Proper content type was not used.";
            http_response_code(404);
        }
        if (isset($_POST['user_id']) && $_POST['user_id'] != "") {
            if (isset($_POST['full_name']) && $_POST['full_name'] != "" || isset($_POST['email']) && $_POST['email'] != "" || isset($_POST['theme']) && $_POST['theme'] != "" || isset($_POST['image']) && $_POST['image'] != "" || isset($_POST['gender']) && $_POST['gender'] != "" || isset($_POST['dob']) && $_POST['dob'] != "") {
                $user_id = addslashes($_POST["user_id"]);
                $statement = $users->read($user_id);
                if ($statement != null) {
                    if ($statement->rowCount() > 0) {
                        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                            $data_security->decryption_key = $row["decryption_key"];
                            $data_security->decryption_iv = $row["decryption_iv"];
                            $data_security->encryption_key = base64_decode($row["decryption_key"]);
                            $data_security->encryption_iv = base64_decode($row["decryption_iv"]);

                            $first_name = $row["first_name"];
                            $last_name = $row["last_name"];
                            if (isset($_POST['full_name']) && $_POST['full_name'] != "") {
                                $full_name_split = explode(" ", addslashes($_POST["full_name"]));
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
                            }

                            $email = $row["email"];
                            if (isset($_POST['email']) && $_POST['email'] != "") {
                                $email = $data_security->encrypt(addslashes($_POST["email"]));
                            }

                            $theme = $row["theme"];
                            if (isset($_POST['theme']) && $_POST['theme'] != "") {
                                $theme = $data_security->encrypt(addslashes($_POST["theme"]));
                            }

                            $gender = $row["gender"];
                            if (isset($_POST['gender']) && $_POST['gender'] != "") {
                                $gender = $data_security->encrypt(addslashes($_POST["gender"]));
                            }

                            $dob = $row["dob"];
                            if (isset($_POST['dob']) && $_POST['dob'] != "") {
                                $dob = $data_security->encrypt(addslashes($_POST["dob"]));
                            }

                            $image_path = $row["image_path"];
                            if (isset($_POST['image']) && $_POST['image'] != "") {
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

                            if ($users->update($user_id, $email, $first_name, $last_name, $image_path, $gender, $dob, $theme)) {
                                $status["response"] = "Success";
                                $status["message"] = "Account updated successfully.";
                                http_response_code(200);
                            } else {
                                $status["response"] = "Failed";
                                $status["message"] = "Account updation failed.";
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
                $status["message"] = "There is nothing to update.";
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
