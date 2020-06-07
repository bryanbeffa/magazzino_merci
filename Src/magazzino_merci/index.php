<?php
session_start();

require_once __DIR__ . '/application/models/DbManager.php';
require_once __DIR__ . '/application/models/UserManager.php';

// carico il file di configurazione
require 'application/config/config.php';

try {
    //check connection status
    DbManager::connect();

} catch (PDOException $ex) {

    require_once __DIR__ . '/application/views/global/head.php';
    require_once __DIR__ . '/application/views/error/error.php';

    //user logout
    UserManager::logout();
    exit;
}

// carico le classi dell'applicazione
require 'application/libs/application.php';

// faccio partire l'applicazione
$app = new Application();
