<?php
class login_info
{
    private $connection = null;
    private $db_table = "login_info";
    private $users = null;
    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->users = new users($this->connection);
    }

    public function insert($user_id, $device_token, $device_brand, $device_model, $app_version, $decryption_key, $decryption_iv, $os_version)
    {
        $statement = $this->users->read($user_id);
        if ($statement && $statement->rowCount() > 0) {
            if ($this->delete($user_id)) {
                $statement = $this->connection->prepare("insert into " . $this->db_table . " (user_id, device_token, device_brand, device_model, app_version, decryption_key, decryption_iv, os_version, done_at) values (?, ?, ?, ?, ?, ?, ?, ?, now())");
                if ($statement->execute(array($user_id, $device_token, $device_brand, $device_model, $app_version, $decryption_key, $decryption_iv, $os_version))) {
                    return true;
                } else {
                    return false;
                }
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
}
