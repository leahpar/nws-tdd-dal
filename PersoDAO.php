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
        // TODO: faire en dynamique avec get_object_vars()

        $sql = "insert into Perso (id, name, hp, mana)
              value (null, :name, :hp, :mana)";

        $this->dal->execute($sql, [
            "name" => $perso->getName(),
            "hp" => $perso->getHp(),
            "mana" => $perso->getMana()
        ]);

        // TODO: vérifier que l'execute est OK

        return $this->dal->lastInsertId();
    }


    public function get(int $id)
    {
        // TODO
        return false;
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
