<?php

class Catalog
{

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/CategoryManager.php';
        require_once __DIR__ . '/../models/ArticleManager.php';
        require_once __DIR__ . '/../models/CategoryModel.php';
        require_once __DIR__ . '/../models/OrderManager.php';
        require_once __DIR__ . '/../models/OrderModel.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = CATALOGO;
    }

    public function index()
    {
        $this->showStoredArticles();
    }

    /**
     * Method that shows the stored articles
     */
    public function showStoredArticles()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {
                $navbar = 'admin';
            } else if (UserManager::getPermission($_SESSION['email']) == OPERATORE) {
                $navbar = 'operator';
            } else if (UserManager::getPermission($_SESSION['email']) == BASE) {
                $navbar = 'base';
            } else if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {
                $navbar = 'supplier';
            } else {
                header('Location: ' . URL . 'home');
                exit;
            }

            $filters = array();
            $filters['text'] = '%';
            $filters['category'] = '%';

            //text filter
            if (isset($_POST['text_filter']) && !empty($_POST['text_filter'])) {
                $_SESSION['text_filter'] = Validator::testInput($_POST['text_filter']);
            } else {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $_SESSION['text_filter'] = '';
                }
            }
            (isset($_SESSION['text_filter'])) ? $filters['text'] = '%' . $_SESSION['text_filter'] . '%' : null;

            //category filter
            if (isset($_POST['category_filter']) && !empty($_POST['category_filter'])) {
                $_SESSION['category_filter'] = Validator::testInput($_POST['category_filter']);
            } else {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $_SESSION['category_filter'] = '';
                }
            }
            (isset($_SESSION['category_filter'])) ? $filters['category'] = $_SESSION['category_filter'] : null;

            //expire date filter
            (isset($_POST['expire_date_filter']) && !empty($_POST['expire_date_filter'])) ? $filters['expire_date'] = date_format(date_create(Validator::testInput($_POST['expire_date_filter'])), 'Y-m-d') : null;

            //available date filter
            (isset($_POST['available_date_filter']) && !empty($_POST['available_date_filter'])) ? $filters['available_date'] = date_format(date_create(Validator::testInput($_POST['available_date_filter'])), 'Y-m-d') : null;

            //check offset and limit variables
            if (isset($_GET['page'])) {
                $page = intval(Validator::testInput($_GET['page']));

                if ($page < DEFAULT_PAGE) {
                    $page = DEFAULT_PAGE;
                }
            } else {
                $page = DEFAULT_PAGE;
            }
            $limit = DEFAULT_ARTICLE_LIMIT;

            //calculate number of pages
            $number_pages = ceil(ArticleManager::getStoredArticlesNumber($filters) / $limit);
            if ($number_pages < $page) {
                $page = DEFAULT_PAGE;
            }

            $categories = CategoryManager::getCategoriesList();
            $stored_articles = ArticleManager::getStoredArticlesList(($page - 1) * $limit, $limit, $filters);

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_' . $navbar . '.php';
            require_once __DIR__ . '/../views/catalog/catalog.php';

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
     * Method that tries to make the user order request
     */
    public function makeOrderRequest()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == BASE) {

                //check post variables
                if (isset($_POST['delivery_date']) && isset($_POST['article_id']) && isset($_POST['article_quantity']) && !empty($_POST['article_id']) && !empty($_POST['article_quantity']) && !empty($_POST['delivery_date'])) {

                    //test input
                    $article_id = Validator::testInput($_POST['article_id']);
                    $article_quantity = Validator::testInput($_POST['article_quantity']);
                    $delivery_date = Validator::testInput($_POST['delivery_date']);
                    $article = ArticleManager::getArticleById($article_id);
                    $expire_date = $article['data_scadenza'];
                    $available_date = $article['disponibile_il'];

                    if (Validator::isDeliveryDateValid($delivery_date, $expire_date, $available_date)) {

                        //create order
                        $order = new OrderModel($article_quantity, $article_id, UserManager::getUserByEmail($_SESSION['email'])['id'], $delivery_date);

                        //try to make the order request
                        if (OrderManager::addOrder($order)) {
                            MessageManager::setSuccessMsg('Richiesta inviata. Il tuo ordine verrà visionato');
                            MessageManager::unsetErrorMsg();
                        } else {
                            MessageManager::setErrorMsg('Impossibile richiedere questo articolo');
                        }
                    } else {
                        MessageManager::setErrorMsg('La data di consegna non è valida');
                    }
                }
            }
            header('Location: ' . URL . 'catalog');
            exit;
        }
        header('Location: ' . URL . 'home');
    }
}