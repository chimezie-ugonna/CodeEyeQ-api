<?php
class users
{
    private $connection = null;
    private $db_table = "users";
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function create($user_id, $email, $first_name, $last_name, $decryption_key, $decryption_iv, $theme)
    {
        $statement = $this->connection->prepare("insert into " . $this->db_table . " (user_id, email, first_name, last_name, image_path, gender, dob, decryption_key, decryption_iv, theme, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now()) on conflict (user_id) do nothing");
        if ($statement->execute(array($user_id, $email, $first_name, $last_name, "", "", "", $decryption_key, $decryption_iv, $theme))) {
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

    public function update($user_id, $email, $first_name, $last_name, $image_path, $gender, $dob, $theme)
    {
        $statement = $this->connection->prepare("update " . $this->db_table . " set email = ?, first_name = ?, last_name = ?, image_path = ?, gender = ?, dob = ?, theme = ? where user_id = ?");
        if ($statement->execute(array($email, $first_name, $last_name, $image_path, $gender, $dob, $theme, $user_id))) {
            return $statement;
        } else {
            return null;
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
}
