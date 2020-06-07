<?php

/**
 * Configurazione
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */

/**
 * Configurazione di : Error reporting
 * Utile per vedere tutti i piccoli problemi in fase di sviluppo, in produzione solo quelli gravi
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Configurazione di : URL del progetto
 */
define('URL', 'http://localhost:8080/magazzino_merci/');

/**
 * Permission constants
 */
define('ADMIN', 1);
define('BASE', 2);
define('OPERATORE', 3);
define('FORNITORE', 4);

/**
 * Nav bar items
 */
define('UTENTI', 1);
define('CATEGORIE', 2);
define('CATALOGO', 3);
define('REPORT', 4);
define('ORDINI', 5);
define('FORNITORI', 6);
define('NOTIFICHE', 7);

/**
 * Constant that defines the type of operation used in the log
 */
define('REJECT_ORDER_REQUEST', 1);
define('ACCEPT_ORDER_REQUEST', 2);
define('ARTICLE_ALMOST_EXPIRED', 3);
define('ARTICLE_EXPIRED', 4);
define('ARTICLE_DELETED', 5);
define('ARTICLE_STORED', 6);
define('NEW_ORDER_REQUEST', 7);

/**
 * Constant that defines the number of notifications to show to the user.
 */
define('NUM_NOTIFICATIONS', 3);

/**
 * Pagination constants
 */
define('DEFAULT_ARTICLE_LIMIT', 6);
define('DEFAULT_ARTICLE_OFFSET', 0);
define('DEFAULT_PAGE', 1);
define('MAX_NUM_PAGES', 5);

