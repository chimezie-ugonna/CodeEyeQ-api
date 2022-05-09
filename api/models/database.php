<?php

class database
{
    private $host;
    private $port;
    private $db_name;
    private $user;
    private $password;
    private $sslmode;
    private $connection;

    function __construct()
    {
        $this->host = $_SERVER["DB_HOST"];
        $this->port = $_SERVER["DB_PORT"];
        $this->db_name = $_SERVER["DB_NAME"];
        $this->user = $_SERVER["DB_USER"];
        $this->password = $_SERVER["DB_PASSWORD"];
        $this->sslmode = $_SERVER["SSL_MODE"];
    }

    public function connect()
    {
        $this->connection = null;

        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";user=" . $this->user . ";password=" . $this->password . ";sslmode=" . $this->sslmode . ";";

            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Databse connection failed: " . $e->getMessage();
        }

        return $this->connection;
    }
}
