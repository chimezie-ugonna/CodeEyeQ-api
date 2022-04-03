<?php
class login_info
{
    private $connection = null;
    private $db_table = "login_info";
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function insert($user_id, $device_token, $device_brand, $device_model, $app_version, $encryption_key_, $encryption_iv_, $os_version)
    {
        if ($this->delete($user_id)) {
            $statement = $this->connection->prepare("insert into " . $this->db_table . " (user_id, device_token, device_brand, device_model, app_version, encryption_key, encryption_iv, os_version, done_at) values (?, ?, ?, ?, ?, ?, ?, ?, now())");
            if ($statement->execute(array($user_id, $device_token, $device_brand, $device_model, $app_version, $encryption_key_, $encryption_iv_, $os_version))) {
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
        $statement = $this->connection->prepare("delete from " . $this->db_table . " where user_id = ?");
        if ($statement->execute(array($user_id))) {
            return true;
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
        $statement = $this->connection->prepare("select column_name from information_schema.columns where table_name = ?");
        if ($statement->execute(array($this->db_table))) {
            return $statement;
        } else {
            return null;
        }
    }
}
