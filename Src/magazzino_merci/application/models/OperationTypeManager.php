<?php


class OperationTypeManager
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
     * Method that returns the operation type
     * @param $id operation type id
     * @return bool return the operation
     */
    public static function getOperationTypeById($id)
    {
        try {
            //get categories list
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT * FROM tipo_operazione WHERE id = :id");

            //bind params
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);

            $prepared_query->execute();
            return $prepared_query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return false;
        }
    }
}