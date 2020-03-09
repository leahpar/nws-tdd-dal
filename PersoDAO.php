<?php

require_once "Perso.php";

class PersoDAO
{

    /** @var DAL */
    private $dal;

    public function __construct($dal)
    {
        $this->dal = $dal;
    }

    /**
     * Sauvegarde une entité en base
     * @param Perso $perso
     * @return int|string
     */
    public function add(Perso $perso)
    {
        // Données "brutes"
        $data = $perso->dehydrate();

        // Insertion
        $res = $this->dal->add("Perso", $data);

        if ($res) {
            // Récupération de l'ID inséré
            $id = $this->dal->lastInsertId();
            $perso->setId($id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Récupère un Perso sur l'ID
     * @param int $id
     * @return Perso|null
     */
    public function get(int $id): ?Perso
    {
        // Données à récupérer
        $keys = array_keys((new Perso())->dehydrate());

        // Requête
        $data = $this->dal->getById("Perso", $keys, $id);

        // Aucun résultat
        if ($data === false) return null;

        // Hydratation
        $perso = new Perso($data);
        return $perso;
    }

    /**
     * Mise à jour d'un Perso
     * @param Perso $perso
     * @return bool
     */
    public function update(Perso $perso)
    {
        $data = $perso->dehydrate();

        return $this->dal->update("Perso", $data, $perso->getId());
    }

    /**
     * Suppression d'un Perso
     * @param Perso $perso
     * @return bool
     */
    public function delete(Perso $perso)
    {
        return $this->dal->delete("Perso", $perso->getId());
    }


    /**
     * Recherche générique d'un unique Perso
     * @param $params
     * @return Perso|null
     */
    public function searchOne($params)
    {
        $res = $this->search($params);
        if (count($res) == 1) {
            return $res[0];
        } else {
            return null;
        }
    }

    /**
     * Recherche générique de Persos
     * @param $params
     * @return array
     */
    public function search($params)
    {
        $rows = $this->dal->search("Perso", $params);

        $persos = [];
        foreach ($rows as $row) {
            $persos[] = new Perso($row);
        }

        return $persos;
    }

    public function __call(string $name, array $arguments)
    {
        if (substr($name, 0, 8) == "searchBy") {
            $attr = strtolower(substr($name, 8));
            return $this->search([$attr => $arguments[0]]);
        }
        else if (substr($name, 0, 5) == "getBy") {
            $attr = strtolower(substr($name, 5));
            return $this->searchOne([$attr => $arguments[0]]);
        }
        else {
            throw new \Exception("Methode inconnue");
        }
    }

    // ...
}
