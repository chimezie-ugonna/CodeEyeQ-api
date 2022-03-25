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
        $query = "insert into " . $this->db_table . " (user_id, device_token, device_brand, device_model, app_version, encryption_key, encryption_iv, os_version, done_at) values (?, ?, ?, ?, ?, ?, ?, ?, now())";
        $statement = $this->connection->prepare($query);
        $statement->execute(array($user_id, $device_token, $device_brand, $device_model, $app_version, $encryption_key_, $encryption_iv_, $os_version));
        return $statement;
    }

    public function delete($user_id)
    {
        $query = "delete from " . $this->db_table . " where user_id = ?";
        $statement = $this->connection->prepare($query);
        $statement->execute(array($user_id));
        return $statement;
    }
}
