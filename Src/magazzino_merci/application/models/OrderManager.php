<?php


class OrderManager
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
     * Method that returns the unverified orders list
     */
    public static function getUnverifiedOrdersList()
    {
        try {
            //get unverified order requests list
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT * FROM ordine WHERE accettato IS NULL");
            $prepared_query->execute();

            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {

            return false;
        }
    }

    /**
     * Method that tries to add a new order
     * @param $order order
     * @return bool return if the result was successful
     */
    public static function addOrder($order)
    {
        try {
            //add order requests
            self::getConnection();
            $prepared_query = self::$conn->prepare("INSERT INTO ordine(quantita_ordine, id_utente, id_articolo, data_consegna) values (:quantita, :id_utente, :id_articolo, :data_consegna)");

            //get params
            $quantity = $order->getQuantity();
            $user_id = $order->getUserId();
            $article_id = $order->getArticleId();
            $delivery_date = $order->getDeliveryDate();

            //bind params
            $prepared_query->bindParam(':quantita', $quantity, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_utente', $user_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_articolo', $article_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':data_consegna', $delivery_date, PDO::PARAM_STR);

            $prepared_query->execute();
            return true;

        } catch (PDOException $ex) {
            exit($ex);
            return false;
        }
    }

    /**
     * Method that tries to reject the order request
     * @param $user_id user id
     * @param $article_id article id
     * @param $order_id order id
     * @return bool return if the result was successful
     */
    public static function rejectOrderRequest($user_id, $article_id, $order_id)
    {
        try {
            //reject order request
            self::getConnection();
            $prepared_query = self::$conn->prepare("UPDATE ordine set accettato = 0 WHERE id = :id_ordine");

            //bind params
            $prepared_query->bindParam(':id_ordine', $order_id, PDO::PARAM_INT);
            $prepared_query->execute();

            //insert into log
            $prepared_query = self::$conn->prepare("INSERT INTO log (utente_richiedente, id_tipo_operazione, id_articolo, id_ordine) values (:utente_richiedente, :id_tipo_operazione, :id_articolo, :id_ordine)");

            //bind params
            $prepared_query->bindParam(':utente_richiedente', $user_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_articolo', $article_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_ordine', $order_id, PDO::PARAM_INT);
            $prepared_query->bindValue(':id_tipo_operazione', REJECT_ORDER_REQUEST, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that tries to accept the user order request.
     * @param $user_id user id
     * @param $article_id article id
     * @param $date order date
     * @param $quantity article order quantity
     * @return bool return if the result was successful
     */
    public static function acceptOrderRequest($user_id, $article_id, $order_id, $quantity)
    {
        try {
            //accept order request
            self::getConnection();

            $prepared_query = self::$conn->prepare("UPDATE ordine set accettato = 1, quantita_ordine = :quantita_ordine WHERE id = :id_ordine");

            //bind params
            $prepared_query->bindParam(':id_ordine', $order_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':quantita_ordine', $quantity, PDO::PARAM_INT);
            $prepared_query->execute();

            //insert into log
            $prepared_query = self::$conn->prepare("INSERT INTO log (utente_richiedente, id_tipo_operazione, id_articolo, id_ordine) values (:utente_richiedente, :id_tipo_operazione, :id_articolo, :id_ordine)");

            //bind params
            $prepared_query->bindParam(':utente_richiedente', $user_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_articolo', $article_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':id_ordine', $order_id, PDO::PARAM_INT);
            $prepared_query->bindValue(':id_tipo_operazione', ACCEPT_ORDER_REQUEST, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that returns the desired order.
     * @param $order_id order id
     * @return bool return if the result was successful
     */
    public static function getOrderRequest($order_id)
    {
        try {
            //get order request
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT * FROM ordine WHERE id = :id_ordine");

            //bind params
            $prepared_query->bindParam(':id_ordine', $order_id, PDO::PARAM_INT);
            $prepared_query->execute();

            return $prepared_query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Method that returns the user order request
     * @param $user_id user id
     * @return order requests list
     */
    public static function getUserOrderRequests($user_id)
    {
        try {
            //get user order requests list
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT * FROM ordine WHERE id_utente = :id_utente order by data_ordine DESC");

            //bind params
            $prepared_query->bindParam(':id_utente', $user_id, PDO::PARAM_INT);
            $prepared_query->execute();

            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Method that deletes every order of the desired article
     * @param $article_id article id
     * @return if the operation was successful
     */
    public static function deleteArticleOrders($article_id)
    {
        try {
            //get orders lists
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT id, id_utente, id_articolo FROM ordine WHERE id_articolo = :id_articolo AND accettato IS NULL");

            //bind param
            $prepared_query->bindParam(':id_articolo', $article_id, PDO::PARAM_INT);
            $prepared_query->execute();
            $orders = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

            //insert into log table
            foreach ($orders as $order) {
                $prepared_query = self::$conn->prepare("INSERT INTO log (utente_richiedente, id_tipo_operazione, id_articolo, id_ordine) values (:utente_richiedente, :id_tipo_operazione, :id_articolo, :id_ordine)");
                $prepared_query->bindParam(':utente_richiedente', $order['id_utente'], PDO::PARAM_INT);
                $prepared_query->bindParam(':id_ordine', $order['id'], PDO::PARAM_INT);
                $prepared_query->bindParam(':id_articolo', $order['id_articolo'], PDO::PARAM_INT);
                $prepared_query->bindValue(':id_tipo_operazione', REJECT_ORDER_REQUEST, PDO::PARAM_INT);
                $prepared_query->execute();
            }

            //reject all requests
            $prepared_query = self::$conn->prepare("UPDATE ordine set accettato = 0 WHERE id_articolo = :id_articolo AND accettato is null");
            $prepared_query->bindParam(':id_articolo', $article_id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }
}