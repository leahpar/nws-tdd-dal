<?php

require_once "config.php";

class DAL
{

    /** @var PDO */
    private $dbh = null;

    /** @var PDOStatement */
    private $lastStmt = null;

    public function connect()
    {
        try {
            $this->dbh = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return $this->isConnected();
    }

    public function isConnected()
    {
        return $this->dbh !== null;
    }

    public function disconnect()
    {
        $this->dbh = null;
        return true;
    }

    public function execute(string $query, array $data)
    {
        try {
            $stmt = $this->dbh->prepare($query);

            foreach ($data as $key => $value) {
                $stmt->bindParam(':' . $key, $data[$key]);
            }
            $this->lastStmt = $stmt;
            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function lastInsertId()
    {
        try {
            return $this->dbh->lastInsertId();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function fetchData()
    {
        if ($this->lastStmt == null) {
            return null;
        }

        return $this->lastStmt->fetchAll();
    }




}
