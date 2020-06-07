<?php


class CategoryManager
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
     * Method that returns the categories list
     */
    public static function getCategoriesList()
    {
        try {
            //get categories list
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT * FROM categoria");
            $prepared_query->execute();
            $categories = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

            return $categories;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that returns the number of times that the category is assigned to the articles
     * @param $id category id
     * @param bool $stored if get stored articles or available articles
     * @return int number of times.
     */
    public static function getArticlesNumberById($id, bool $stored)
    {
        try {
            //get categories list
            self::getConnection();

            if ($stored) {
                $prepared_query = self::$conn->prepare("SELECT count(*) as num_articoli FROM articoli WHERE id_categoria = :id AND eliminato = 0 AND in_magazzino = 1");
            } else {
                $prepared_query = self::$conn->prepare("SELECT count(*) as num_articoli FROM articoli WHERE id_categoria = :id AND eliminato = 0 AND in_magazzino = 0");
            }
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            $times = $prepared_query->fetch(PDO::FETCH_ASSOC);
            return $times;

        } catch (PDOException $ex) {
            return 0;
        }
    }

    /**
     * Method that tries to delete the desired category.
     * @param $id category id
     * @return bool if the operation was successful
     */
    public static function deleteCategory($id)
    {
        try {
            self::getConnection();
            $prepared_query = self::$conn->prepare("DELETE FROM categoria WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }


    /**
     * Method that tries to add the category.
     * @param $category category to add
     * @return bool if the operation was successful
     */
    public static function addCategory($category)
    {
        try {
            self::getConnection();
            require_once __DIR__ . '/CategoryModel.php';

            //get params
            $name = $category->getName();
            $id = $category->getUserId();

            //add category
            $prepared_query = self::$conn->prepare("INSERT INTO categoria (nome, id_utente) values(:nome, :id)");

            //bind params
            $prepared_query->bindParam(':nome', $name, PDO::PARAM_STR);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Get category by id.
     * @param $id user id
     * @return |null the desired category
     */
    public static function getCategoryById($id)
    {
        try {
            self::getConnection();

            //prepare query
            $prepared_query = self::$conn->prepare("SELECT * FROM categoria WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return $prepared_query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Method that tries to update the desired category.
     * @param $category category
     * @param $id category id
     * @return bool if the operation was successful
     */
    public static function updateCategory($category, $id)
    {
        try {
            self::getConnection();

            $sql = "UPDATE categoria set nome = :nome WHERE id = :id";

            //prepare query
            $prepared_query = self::$conn->prepare($sql);

            //get params
            $name = $category->getName();

            //bind params
            $prepared_query->bindParam(':nome', $name, PDO::PARAM_STR);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }
}