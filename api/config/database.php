<?php
class database
{
    private $host = "ec2-44-194-54-186.compute-1.amazonaws.com";
    private $port = "5432";
    private $db_name = "dco8bgoaljvb7";
    private $user = "uaqteoyzlwegvo";
    private $password = "b210289ab147c6c6c2ff023fc8b91a61cd29708140f2b06e9c9dbaf06da94e42";
    private $sslmode = "require";
    private $connection;

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
