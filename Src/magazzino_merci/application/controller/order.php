<?php


class Order
{
    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/CategoryManager.php';
        require_once __DIR__ . '/../models/OrderModel.php';
        require_once __DIR__ . '/../models/OrderManager.php';
        require_once __DIR__ . '/../models/ArticleManager.php';
        require_once __DIR__ . '/../models/CategoryModel.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = ORDINI;
    }

    public function index()
    {
        $this->showOrderArticlesPage();
    }

    /**
     * Method that shows the available articles to order
     */
    public function showOrderArticlesPage()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {
                $navbar = 'admin';
            } else if (UserManager::getPermission($_SESSION['email']) == OPERATORE) {
                $navbar = 'operator';

            } else {
                header('Location: ' . URL . 'catalog');
                exit;
            }

            $available_articles = ArticleManager::getSupplierArticlesList();

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_' . $navbar . '.php';
            require_once __DIR__ . '/../views/order/order_new_articles_manager.php';

            //show success message
            if (MessageManager::getSuccessMsg()) {
                echo "<script>$.notify('" . MessageManager::getSuccessMsg() . "', 'success');</script>";
                MessageManager::unsetSuccessMsg();
            }

            //show error message
            if (MessageManager::getErrorMsg()) {
                echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
                MessageManager::unsetErrorMsg();
            }
            exit;

        }
        header('Location: ' . URL . 'home');
    }

    /**
     * Method that store a new article
     */
    public function orderArticle()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN || UserManager::getPermission($_SESSION['email']) == OPERATORE) {

                //check post variables
                if (isset($_POST['article_id']) && !empty($_POST['article_id'])) {

                    $id = Validator::testInput($_POST['article_id']);

                    //try to store the article
                    if (ArticleManager::storeArticle($id)) {
                        MessageManager::setSuccessMsg("Il prodotto ora si trova in magazzino");
                        MessageManager::unsetErrorMsg();
                    } else {
                        MessageManager::setErrorMsg('Impossibile ordinare questo articolo');
                    }
                    header('Location: ' . URL . 'order');
                    exit;
                }
            }
            header('Location: ' . URL . 'catalog');
            exit;
        }
        header('Location: ' . URL . 'home');

    }

    /**
     * Method that shows the unverified orders.
     */
    public function showUnverifiedOrders()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {
                $navbar = 'admin';
            } else if (UserManager::getPermission($_SESSION['email']) == OPERATORE) {
                $navbar = 'operator';

            } else {
                header('Location: ' . URL . 'catalog');
                exit;
            }

            $unverified_order = OrderManager::getUnverifiedOrdersList();

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_' . $navbar . '.php';
            require_once __DIR__ . '/../views/order/user_orders_manager.php';

            //show success message
            if (MessageManager::getSuccessMsg()) {
                echo "<script>$.notify('" . MessageManager::getSuccessMsg() . "', 'success');</script>";
                MessageManager::unsetSuccessMsg();
            }

            //show error message
            if (MessageManager::getErrorMsg()) {
                echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
                MessageManager::unsetErrorMsg();
            }

            exit;
        }
        header('Location: ' . URL . 'home');

    }

    /**
     * Method that tries to reject the user request
     */
    public function rejectOrderRequest()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == BASE || UserManager::getPermission($_SESSION['email']) == FORNITORE) {
                header('Location: ' . URL . 'catalog');
                exit;
            }

            //check post variables
            if (isset($_POST['user_id']) && isset($_POST['article_id']) && isset($_POST['order_id']) && !empty($_POST['user_id']) && !empty($_POST['article_id']) && !empty($_POST['order_id'])) {

                //test inputs
                $user_id = Validator::testInput($_POST['user_id']);
                $article_id = Validator::testInput($_POST['article_id']);
                $order_id = Validator::testInput($_POST['order_id']);

                //try to reject order request
                if (OrderManager::rejectOrderRequest($user_id, $article_id, $order_id)) {
                    MessageManager::setSuccessMsg('La richiesta è stata rifiutata con successo');
                    MessageManager::unsetErrorMsg();
                } else {
                    MessageManager::setErrorMsg('Impossibile rifiutare la richiesta');
                }
            }

            header('Location: ' . URL . 'order/showUnverifiedOrders');
            exit;

        }
        header('Location: ' . URL . 'home');
    }

    /**
     * Method that tries to accept the user request
     */
    public function acceptOrderRequest()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == BASE || UserManager::getPermission($_SESSION['email']) == FORNITORE) {
                header('Location: ' . URL . 'catalog');
                exit;
            }

            //check post variables
            if (isset($_POST['user_id']) && isset($_POST['article_id']) && isset($_POST['order_id']) && !empty($_POST['user_id']) && !empty($_POST['article_id']) && !empty($_POST['order_id'])) {

                //test inputs
                $user_id = Validator::testInput($_POST['user_id']);
                $article_id = Validator::testInput($_POST['article_id']);
                $order_id = Validator::testInput($_POST['order_id']);

                //check if the product quantity is still available
                $article_quantity = intval(ArticleManager::getArticleById($article_id)['quantita']);
                if ($article_quantity >= 1) {

                    $order_quantity = intval(OrderManager::getOrderRequest($order_id)['quantita_ordine']);

                    //check if the user quantity isn't higher than available article quantity
                    if ($article_quantity <= $order_quantity) {
                        //set order quantity to max article quantity
                        $order_quantity = $article_quantity;
                    }

                    //try to accept the order
                    if (OrderManager::acceptOrderRequest($user_id, $article_id, $order_id, $order_quantity)) {

                        //set new article quantity
                        $new_article_quantity = $article_quantity - $order_quantity;

                        //drop all orders request for this article
                        if($new_article_quantity <= 0){
                            OrderManager::deleteArticleOrders($article_id);
                        }

                        ArticleManager::updateArticleQuantity($article_id, $new_article_quantity);
                        MessageManager::setSuccessMsg('Richiesta accetta con successo');
                        MessageManager::unsetErrorMsg();
                    } else {
                        MessageManager::setErrorMsg('Impossibile accettare la richiesta');
                    }
                } else {
                    //try to reject order request
                    if (OrderManager::rejectOrderRequest($user_id, $article_id, $order_id)) {
                        MessageManager::setErrorMsg('Richiesta rifiutata. Questo articolo non è più disponibile');
                    } else {
                        MessageManager::setErrorMsg('Impossibile elaborare la richiesta');
                    }
                }
            }

            header('Location: ' . URL . 'order/showUnverifiedOrders');
            exit;

        }
        header('Location: ' . URL . 'home');
    }

    /**
     * Method that shows the user order requests history
     */
    public function showUserHistory()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == BASE) {

                $orders = OrderManager::getUserOrderRequests(UserManager::getUserByEmail($_SESSION['email'])['id']);

                require_once __DIR__ . '/../views/global/head.php';
                require_once __DIR__ . '/../views/global/navbar_base.php';
                require_once __DIR__ . '/../views/order/user_orders_history.php';

                exit;

            }

            header('Location: ' . URL . 'order/showUnverifiedOrders');
            exit;
        }
        header('Location: ' . URL . 'home');
    }
}