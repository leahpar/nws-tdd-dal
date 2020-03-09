<?php

require_once "Test.php";
require_once "Perso.php";
require_once "PersoDAO.php";
require_once "DAL.php";


$dal = new DAL();

/**
 * DAL non connecté
 */

Test::assert("DAL non connecté", $dal->isConnected() == false);

/**
 * Connexion DAL
 */

Test::assert("Connexion DAL", $dal->connect());

/**
 * DAL connecté
 */

Test::assert("DAL connecté", $dal->isConnected());


/**
 * Déconnexion
 */

Test::assert("Déconnexion DAL",
    $dal->disconnect() && ($dal->isConnected() == false));

/**
 * Requête SQL sans paramètre
 */

$dal->connect();
$sql = 'SELECT * FROM perso';
Test::assert("Requête SQL sans paramètre",
    $dal->execute($sql, []));

/**
 * Requête SQL avec paramètres
 */

$dal->connect();
$sql = 'SELECT * FROM perso where name = :name';
Test::assert("Requête SQL avec paramètres",
    $dal->execute($sql, ["name" => "jean"]));



/**
 * Ajout d'un perso
 */

$dao = new PersoDAO($dal);

$perso = new Perso();
$perso->setName("toto");
$perso->setHp("100");
$perso->setMana("10");
Test::assert("Ajout d'un perso 1/2", $dao->add($perso) == true);
Test::assert("Ajout d'un perso 2/2", $perso->getId() > 0);
$id = $perso->getId();


/**
 * Sélection d'un perso
 */

$perso2 = $dao->get($id);
Test::assert("Sélection d'un perso", $perso == $perso2);

/**
 * Mise à jour d'un perso
 * (celui qu'on vient de créer)
 */

$perso->setName("toto2");
Test::assert("Mise à jour d'un perso 1/2", $dao->update($perso));
$perso2 = $dao->get($id);
Test::assert("Mise à jour d'un perso 2/2", $perso == $perso2);

/**
 * Suppression d'un perso
 * (celui qu'on vient de créer)
 */

Test::assert("Suppression d'un perso", $dao->delete($perso));


/**
 * Sélection d'un perso inexistant
 * (celui qu'on vient de supprimer)
 */

$perso2 = $dao->get($id);
Test::assert("Sélection d'un perso inexistant", $perso2 == null);

/**
 * Recherche d'un perso
 */

$perso = new Perso();
$perso->setName(uniqid());
$perso->setHp(rand(0, 100));
$perso->setMana(rand(0, 100));
$dao->add($perso);

$persoLst = $dao->searchByName($perso->getName());
Test::assert("Recherche d'un perso 1...", count($persoLst) >= 1);
Test::assert("Recherche d'un perso 2...", $perso->getName() == $persoLst[0]->getName());

$persoLst = $dao->searchByHp($perso->getHp());
Test::assert("Recherche d'un perso 3...", count($persoLst) >= 1);
Test::assert("Recherche d'un perso 4...", $perso->getHp() == $persoLst[0]->getHp());

$perso2 = $dao->searchOne(["id" => $perso->getId()]);
Test::assert("Recherche d'un perso 5...", $perso2 instanceof Perso);
Test::assert("Recherche d'un perso 6...", $perso2->getId() == $perso->getId());

$persoLst = $dao->searchByMana($perso->getMana());
Test::assert("Recherche d'un perso 7...", count($persoLst) >= 1);
Test::assert("Recherche d'un perso 8...", $perso->getMana() == $persoLst[0]->getMana());

$perso2 = $dao->getById($perso->getId());
Test::assert("Recherche d'un perso 9...", $perso2 instanceof Perso);
Test::assert("Recherche d'un perso 10...", $perso2->getId() == $perso->getId());

