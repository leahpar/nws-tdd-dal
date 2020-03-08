<?php

require_once "Test.php";
require_once "Truc.php";
require_once "TrucDAO.php";
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
 * TODO
 * ...
 */


