<?php

/**
 * @OA\Info(title="CodeEyeQ API", version="1.0")
 */
require_once "login_info.php";
class users
{
    private $connection = null;
    private $db_table = "users";
    private $login_info = null;
    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->login_info = new login_info($this->connection);
    }

    /**
     * @OA\Post(path="/api/v1/users/insert", tags={Users}, @OA\Response(response="200", description="Success"), (response="404", description="Failed"))
     */
    public function insert($user_id, $email, $first_name, $last_name, $encryption_key_, $encryption_iv_, $device_token, $device_brand, $device_model, $app_version, $os_version)
    {
        $statement = $this->connection->prepare("insert into " . $this->db_table . " (user_id, email, first_name, last_name, image_status, image_path, gender, dob, encryption_key, encryption_iv, theme, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now()) on conflict (user_id) do nothing");
        if ($statement->execute(array($user_id, $email, $first_name, $last_name, "default", "", "", "", $encryption_key_, $encryption_iv_, "system"))) {
            if ($this->login_info->insert($user_id, $device_token, $device_brand, $device_model, $app_version, $encryption_key_, $encryption_iv_, $os_version)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function delete($user_id)
    {
        if ($this->login_info->delete($user_id)) {
            $statement = $this->connection->prepare("delete from " . $this->db_table . " where user_id = ?");
            if ($statement->execute(array($user_id))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
