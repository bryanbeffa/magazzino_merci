<?php


class NotificationManager
{
    /**
     * @var attribute that defines the database connection
     */
    private static $conn;

    public function __construct()
    {
        $this->getConnection();
    }

    private static function getConnection()
    {
        self::$conn = DbManager::connect();
    }

    /**
     * Method that returns the notifications list
     */
    public static function getUserNotificationList($user_id, $limit = null)
    {
        try {
            //get user notifications list
            self::getConnection();

            $sql = 'SELECT * FROM log WHERE utente_richiedente = :utente_richiedente ORDER BY data DESC';

            //check if there is a limit
            if ($limit) {
                $sql .= ' LIMIT :limite';
            }
            $prepared_query = self::$conn->prepare($sql);

            //bind params
            $prepared_query->bindParam(':utente_richiedente', $user_id, PDO::PARAM_INT);
            if ($limit) {
                $prepared_query->bindParam(':limite', $limit, PDO::PARAM_INT);
            }

            $prepared_query->execute();
            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return false;
        }
    }
    
    /**
     * Method that returns the entire notifications list
     */
    public static function getFullNotificationList(){
        try {
            //get notification list
            self::getConnection();

            $prepared_query = self::$conn->prepare('SELECT * FROM LOG ORDER BY data DESC');
            $prepared_query->execute();

            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return false;
        }
    }
}