<?php
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

    public function insert($user_id, $email, $first_name, $last_name, $encryption_key_, $encryption_iv_, $device_token, $device_brand, $device_model, $app_version, $os_version, $theme)
    {
        $statement = $this->connection->prepare("insert into " . $this->db_table . " (user_id, email, first_name, last_name, image_path, gender, dob, encryption_key, encryption_iv, theme, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now()) on conflict (user_id) do nothing");
        if ($statement->execute(array($user_id, $email, $first_name, $last_name, "", "", "", $encryption_key_, $encryption_iv_, $theme))) {
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

    public function read($user_id)
    {
        $statement = $this->connection->prepare("select * from " . $this->db_table . " where user_id = ?");
        if ($statement->execute(array($user_id))) {
            return $statement;
        } else {
            return null;
        }
    }

    public function read_column_names()
    {
        $statement = $this->connection->prepare("select column_name from information_schema.columns where table_name = " . $this->db_table);
        if ($statement->execute()) {
            return $statement;
        } else {
            return null;
        }
    }
}
