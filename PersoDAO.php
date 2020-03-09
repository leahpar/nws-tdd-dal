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
        $sql = "insert into Perso (id, name, hp, mana)
              value (null, :name, :hp, :mana)";

        $this->dal->execute($sql, $perso->dehydrate());

        // TODO: vérifier que l'execute est OK
        $id = $this->dal->lastInsertId();
        $perso->setId($id);

        return true;
    }

    public function get(int $id): ?Perso
    {
        $sql = "SELECT id, name, hp, mana from Perso where id = :id";
        $this->dal->execute($sql, ["id" => $id]);

        $data = $this->dal->fetchOne();
        if ($data == null) {
            return null;
        }

        $perso = new Perso($data);
        return $perso;
    }

    public function update(Perso $perso)
    {
        $sql = "update Perso 
                set name = :name,
                    hp   = :hp,
                    mana = :mana";

        $this->dal->execute($sql, $perso->dehydrate());

        // TODO: vérifier que l'execute est OK

        return true;
    }

    public function delete(Perso $perso)
    {
        $sql = "delete from Perso where id = :id";

        return $this->dal->execute($sql, [
            "id" => $perso->getId(),
        ]);
    }

    public function search($params)
    {
        // TODO
        return false;
    }

    // ...
}
