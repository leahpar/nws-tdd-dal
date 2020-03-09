<?php

require_once "config.php";

class DAL
{

    /** @var PDO */
    private $dbh = null;

    /** @var PDOStatement */
    private $lastStmt = null;

    /**
     * Connexion à la BDD
     * @return bool
     */
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

    /**
     * Check si on est connecté
     * @return bool
     */
    public function isConnected()
    {
        return $this->dbh !== null;
    }

    /**
     * Déconnexion de la BDD
     * @return bool
     */
    public function disconnect()
    {
        $this->dbh = null;
        return true;
    }

    /**
     * Exécute une requête
     * @param string $query
     * @param array $data
     * @return bool
     */
    public function execute(string $query, array $data)
    {
        // TODO: vérifier qu'on est bien connecté
        try {
            $stmt = $this->dbh->prepare($query);

            foreach ($data as $key => $value) {
                $stmt->bindParam(':' . $key, $data[$key]);
            }
            // On sauvegarde le statement
            // pour pouvoir faire un fetch dessus si demandé
            $this->lastStmt = $stmt;

            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Retourne le dernier ID inséré
     * (à utiliser dans le cas d'un INSERT)
     * @return int|string
     */
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

    /**
     * Récupère les données de la dernière requête exécutée
     * (à utiliser dans le cas d'un SELECT)
     * @return array|null
     */
    public function fetchData()
    {
        if ($this->lastStmt == null) {
            return null;
        }

        try {
            return $this->lastStmt->fetchAll();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Récupère la donnée de la dernière requête exécutée
     * (à utiliser dans le cas d'un SELECT unique)
     * @return array|null
     */
    public function fetchOne()
    {
        if ($this->lastStmt == null) {
            return null;
        }

        try {
            return $this->lastStmt->fetch();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }


    public function add(string $entity, array $data)
    {
        //$sql = "insert into Perso (id, name, hp, mana)
        //      value (null, :name, :hp, :mana)";


        $sql = "insert into ".$entity." (";
        $sql .= implode(',', array_keys($data));
        $sql .= ") value (";
        $sql .= implode(',', array_map(function ($k) {
            return $k == "id" ? "null" : ":" . $k;
        },
            array_keys($data)));
        $sql .= ")";

        unset($data["id"]);
        return $this->execute($sql, $data);
    }

    public function getById(string $entity, array $keys, int $id)
    {
        $sql = "SELECT ";
        $sql .= implode(',', $keys);
        $sql .= " from " . $entity;
        $sql .= " where id = :id";

        $this->execute($sql, ["id" => $id]);

        return $this->lastStmt->fetch();
    }

    public function update(string $entity, array $data, int $id)
    {
        $sql = "update ".$entity." set ";
        $cols = [];
        foreach ($data as $k => $v) {
            if ($k == "id") continue; // On ignore l'ID ici
            $cols[] = $k . " =  :" . $k;
        }
        $sql .= implode(" ,", $cols);
        $sql .= " WHERE id = :id";

        return $this->execute($sql, $data);
    }

    public function delete(string $entity, int $id)
    {
        $sql = "delete from ".$entity." where id = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    public function search(string $entity, array $params)
    {
        $sql = "SELECT ";
        $sql .= implode(',', array_keys((new Perso())->dehydrate()));
        $sql .= " from " . $entity;

        if (count($params) > 0) {
            $sql .= " Where ";
        }

        $cols = [];
        foreach ($params as $k => $v) {
            $cols[] = $k . " = :" . $k;
        }
        $sql .= implode(" AND ", $cols);
        //var_dump($sql, $params);

        $res = $this->execute($sql, $params);
        if ($res === false) return [];

        return $this->fetchData();
    }


}
