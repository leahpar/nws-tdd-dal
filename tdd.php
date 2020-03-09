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
$id = $dao->add($perso);
Test::assert("Ajout d'un perso", $id > 0);
$perso->setId($id);


/**
 * Sélection d'un perso
 */

$perso2 = $dao->get($id);
Test::assert("Sélection d'un perso", $perso == $perso2);
//Test::assert("Sélection d'un perso", $perso instanceof Perso);
//Test::assert("Sélection d'un perso", $perso->getName() == "toto");
//Test::assert("Sélection d'un perso", $perso->getHp()   == "100");
//Test::assert("Sélection d'un perso", $perso->getMana() == "10");

