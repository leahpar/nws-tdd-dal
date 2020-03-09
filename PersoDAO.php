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

    public function add(Perso $perso)
    {
        $sql = "insert into Perso (id, name, hp, mana)
              value (null, :name, :hp, :mana)";
        $this->dal->execute($sql, [
           "name" => $perso->getName(),
           "hp" => $perso->getHp(),
           "mana" => $perso->getMana()
        ]);

        return $this->dal->lastInsertId();
    }


    public function get(int $id): Perso
    {
        $sql = "SELECT id, name, hp, mana from Perso where id = :id";
        $this->dal->execute($sql, ["id" => $id]);

        $data = $this->dal->fetchData();
        if (count($data) == 0 || count($data) > 1) {
            return null;
        }

        $data = $data[0];

        // TODO: hydratation
        $perso = new Perso();
        $perso->setId($data["id"] ?? null);
        $perso->setName($data["name"] ?? null);
        $perso->setHp($data["hp"] ?? null);
        $perso->setMana($data["mana"] ?? null);
        return $perso;
    }

    public function update(Perso $perso)
    {
        // TODO
        return false;
    }

    public function delete(Perso $perso)
    {
        // TODO
        return false;
    }

    public function search($params)
    {
        // TODO
        return false;
    }

    // ...
}
