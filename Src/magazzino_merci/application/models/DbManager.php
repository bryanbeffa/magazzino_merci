<?php

class DbManager
{
    /**
     * @var string variable that defines the username to use of db.
     */
    private static $username = 'magazzino_merci_admin';

    /**
     * @var string variable that defines the user password of db.
     */
    private static $password = 'MagazzinoMerci2020';

    /**
     * @var string variable that defines the database name.
     */
    private static $db_name = 'magazzino_merci_db';

    /**
     * @var string variable that defines the mysql service port.
     */
    private static $host = 'localhost:3306';

    /**
     * @var string variable that defines the charset.
     */
    private static $charset = "utf8";

    /**
     * @var attributes that defines the connection to db.
     */
    private static $conn;

    /**
     * DbManager constructor.
     * UserModel cannot instance DbManager class but they can use the static function connect.
     */
    private function __construct()
    {
    }

    /**
     * Method that returns if the connection is correctly established
     */
    public static function connect()
    {
        if (!self::$conn) {

            self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=" . self::$charset, self::$username, self::$password);

            // set the PDO error mode to exception
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$conn;
        }
        return self::$conn;
    }
}