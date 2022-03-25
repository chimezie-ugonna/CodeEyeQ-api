<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "models/login_info.php";
class users
{
    private $connection = null;
    private $db_table = "users";
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function insert($user_id, $email, $first_name, $last_name, $encryption_key_, $encryption_iv_)
    {
        $query = "insert into " . $this->db_table . " (user_id, email, first_name, last_name, image_status, image_path, gender, dob, encryption_key, encryption_iv, theme, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now()) on conflict (user_id) do nothing";
        $statement = $this->connection->prepare($query);
        $statement->execute(array($user_id, $email, $first_name, $last_name, "default", "", "", "", $encryption_key_, $encryption_iv_, "system"));
        return $statement;
    }

    public function delete($user_id)
    {
        $login_info = new login_info($this->connection);
        $login_info->delete($user_id);

        $query = "delete from " . $this->db_table . " where user_id = ?";
        $statement = $this->connection->prepare($query);
        $statement->execute(array($user_id));
        return $statement;
    }
}
