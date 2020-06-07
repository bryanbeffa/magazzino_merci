<?php


class ArticleManager
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
     * Method that returns the list of the articles not stored
     */
    public static function getSupplierArticlesList()
    {
        try {
            //get categories list
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT * FROM articoli WHERE eliminato = 0 AND in_magazzino = 0 ORDER BY data_scadenza ASC");
            $prepared_query->execute();
            $articles = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

            return $articles;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that returns the list of the articles stored
     */
    public static function getStoredArticlesList($offset = null, $limit = null, $filters)
    {
        try {

            //get categories list
            self::getConnection();
            $sql = 'SELECT * FROM articoli WHERE eliminato = 0 AND in_magazzino = 1 
                    AND quantita > 0 
                    AND nome like :nome';

            //check if the filters are set
            $sql .= (intval($filters['category']) != 0) ? ' AND  id_categoria like :id_categoria' : null;
            $sql .= (isset($filters['available_date'])) ? ' AND disponibile_il <= :data_disponibilita' : null;
            $sql .= (isset($filters['expire_date'])) ? ' AND data_scadenza <= :data_scadenza' : null;

            if (isset($offset) && isset($limit)) {
                $sql .= " LIMIT :offset, :limit";
                $prepared_query = self::$conn->prepare($sql);
                $prepared_query->bindParam(':offset', $offset, PDO::PARAM_INT);
                $prepared_query->bindParam(':limit', $limit, PDO::PARAM_INT);
            } else {
                $prepared_query = self::$conn->prepare($sql);
            }
            $prepared_query->bindParam(':nome', $filters['text'], PDO::PARAM_STR);

            //bind params if the filters are set
            if (intval($filters['category']) != 0) {
                $prepared_query->bindParam(':id_categoria', $filters['category'], PDO::PARAM_STR);
            }

            if (isset($filters['available_date'])) {
                $prepared_query->bindParam(':data_disponibilita', $filters['available_date'], PDO::PARAM_STR);
            }

            if (isset($filters['expire_date'])) {
                $prepared_query->bindParam(':data_scadenza', $filters['expire_date'], PDO::PARAM_STR);
            }
            $prepared_query->execute();
            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that sets to null the deleted field.
     * @param $id category id
     * @return bool if the operation was successful
     */
    public static function deleteArticle($id)
    {
        try {
            self::getConnection();
            $prepared_query = self::$conn->prepare("UPDATE articoli set eliminato = 1, id_categoria = null WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            //insert into log table
            $prepared_query = self::$conn->prepare("INSERT INTO log (id_articolo, id_tipo_operazione) VALUES (:id_articolo, :id_tipo_operazione)");
            $prepared_query->bindParam(':id_articolo', $id, PDO::PARAM_INT);
            $prepared_query->bindValue(':id_tipo_operazione', ARTICLE_DELETED, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that returns the number of stored article
     */
    public static function getStoredArticlesNumber($filters)
    {
        try {
            self::getConnection();
            $sql = "SELECT count(*) as 'num_articoli' FROM articoli WHERE eliminato = 0 AND in_magazzino = 1 AND quantita > 0 AND nome like :nome";
            $sql .= (intval($filters['category']) != 0) ? ' AND  id_categoria like :id_categoria' : null;
            $sql .= (isset($filters['available_date'])) ? ' AND disponibile_il <= :data_disponibilita' : null;
            $sql .= (isset($filters['expire_date'])) ? ' AND data_scadenza <= :data_scadenza' : null;
            $prepared_query = self::$conn->prepare($sql);

            $prepared_query->bindParam(':nome', $filters['text'], PDO::PARAM_STR);
            if (intval($filters['category']) != 0) {
                $prepared_query->bindParam(':id_categoria', $filters['category'], PDO::PARAM_STR);
            }

            if (isset($filters['available_date'])) {
                $prepared_query->bindParam(':data_disponibilita', $filters['available_date'], PDO::PARAM_STR);
            }

            if (isset($filters['expire_date'])) {
                $prepared_query->bindParam(':data_scadenza', $filters['expire_date'], PDO::PARAM_STR);
            }

            $prepared_query->execute();
            return (intval($prepared_query->fetch(PDO::FETCH_ASSOC)['num_articoli']));

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Get article by id.
     * @param $id article id
     * @return article or null
     */
    public static function getArticleById($id)
    {
        try {
            self::getConnection();

            //prepare query
            $prepared_query = self::$conn->prepare("SELECT * FROM articoli WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return $prepared_query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Method that tries to add the article.
     * @param $article article to add
     * @return bool if the operation was successful
     */
    public static function addArticle($article)
    {
        try {
            self::getConnection();
            require_once __DIR__ . '/ArticleModel.php';

            //get params
            $name = $article->getName();
            $quantity = $article->getQuantity();
            $category_id = $article->getCategoryId();
            $is_stored = $article->isStored();
            $is_deleted = $article->isDeleted();
            $available_date = $article->getAvailableDate();
            $expire_date = $article->getExpireDate();
            $user_id = $article->getUserId();

            //add article
            $prepared_query = self::$conn->prepare("INSERT INTO articoli (nome, quantita, id_categoria, in_magazzino, eliminato, disponibile_il, data_scadenza, id_utente) 
                        values(:nome, :quantita, :id_categoria, :in_magazzino, :eliminato, :disponibile_il, :data_scadenza, :id_utente)");

            //bind params
            $prepared_query->bindParam(':nome', $name, PDO::PARAM_STR);
            $prepared_query->bindParam(':quantita', $quantity, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_categoria', $category_id, PDO::PARAM_STR);
            $prepared_query->bindParam(':in_magazzino', $is_stored, PDO::PARAM_STR);
            $prepared_query->bindParam(':eliminato', $is_deleted, PDO::PARAM_STR);
            $prepared_query->bindParam(':disponibile_il', $available_date, PDO::PARAM_INT);
            $prepared_query->bindParam(':data_scadenza', $expire_date, PDO::PARAM_STR);
            $prepared_query->bindParam(':id_utente', $user_id, PDO::PARAM_INT);
            $prepared_query->execute();

            $_SESSION['last_inserted_article_id'] = self::$conn->lastInsertId();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that tries to store the article.
     * @param $id article id
     * @return bool if the operation was successful
     */
    public static function storeArticle($id)
    {
        try {
            self::getConnection();

            //prepare query
            $prepared_query = self::$conn->prepare("UPDATE articoli set in_magazzino = 1 WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            //insert into log table
            $prepared_query = self::$conn->prepare("INSERT INTO log (id_articolo, id_tipo_operazione) VALUES (:id_articolo, :id_tipo_operazione)");
            $prepared_query->bindParam(':id_articolo', $id, PDO::PARAM_INT);
            $prepared_query->bindValue(':id_tipo_operazione', ARTICLE_STORED, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that updates the article quantity
     * @param $id int article id
     * @param $quantity int new article quantity
     * @return bool if the operation was successful
     */
    public static function updateArticleQuantity($id, $quantity)
    {
        try {
            self::getConnection();

            $sql = 'UPDATE articoli set';
            if ($quantity <= 0) {
                $sql .= ' eliminato = 1, id_categoria = null,';
            }
            $sql .= ' quantita = :quantita WHERE id = :id';

            //prepare query
            $prepared_query = self::$conn->prepare($sql);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->bindParam(':quantita', $quantity, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that sets the article image path.
     * @param $id
     * @param $article_image_path
     * @return bool if the operation was successful
     */
    public static function setArticleImage($id, $article_image_path)
    {
        try {
            self::getConnection();

            //prepare query
            $prepared_query = self::$conn->prepare("UPDATE articoli set percorso_immagine = :percorso_immagine WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->bindParam(':percorso_immagine', $article_image_path, PDO::PARAM_STR);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * @param $article
     * @return bool if the operation was successful
     */
    public static function updateArticle($article, $id)
    {
        try {
            self::getConnection();
            require_once __DIR__ . '/ArticleModel.php';

            //get params
            $name = $article->getName();
            $quantity = $article->getQuantity();
            $category_id = $article->getCategoryId();
            $available_date = $article->getAvailableDate();
            $expire_date = $article->getExpireDate();

            //update article
            $prepared_query = self::$conn->prepare("UPDATE articoli set nome = :nome, quantita = :quantita, id_categoria = :id_categoria, disponibile_il = :disponibile_il, data_scadenza = :data_scadenza
                       WHERE id = :id");

            //bind params
            $prepared_query->bindParam(':nome', $name, PDO::PARAM_STR);
            $prepared_query->bindParam(':quantita', $quantity, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_categoria', $category_id, PDO::PARAM_STR);
            $prepared_query->bindParam(':disponibile_il', $available_date, PDO::PARAM_INT);
            $prepared_query->bindParam(':data_scadenza', $expire_date, PDO::PARAM_STR);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            exit($ex);
            return false;
        }
    }
}