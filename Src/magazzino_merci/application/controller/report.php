<?php


class Report
{
    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/CategoryManager.php';
        require_once __DIR__ . '/../models/ArticleManager.php';
        require_once __DIR__ . '/../models/OrderManager.php';
        require_once __DIR__ . '/../models/OperationTypeManager.php';
        require_once __DIR__ . '/../models/NotificationManager.php';
        require_once __DIR__ . '/../models/CategoryModel.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = REPORT;

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

            } else {
                header('Location: ' . URL . 'catalog');
                exit;
            }

            $filters = array();
            $filters['text'] = '%';
            $filters['category'] = '%';

            //text filter
            if(isset($_POST['text_filter']) && !empty($_POST['text_filter'])){
                $_SESSION['stored_text_filter'] =  Validator::testInput($_POST['text_filter']);
            } else {
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_SESSION['stored_text_filter'] = '';
                }
            }
            (isset($_SESSION['stored_text_filter']))? $filters['text'] = '%' . $_SESSION['stored_text_filter'] . '%': null;

            //category filter
            if(isset($_POST['category_filter']) && !empty($_POST['category_filter'])){
                $_SESSION['stored_category_filter'] =  Validator::testInput($_POST['category_filter']);
            } else {
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_SESSION['stored_category_filter'] = '';
                }
            }
            (isset($_SESSION['stored_category_filter']))? $filters['category'] = $_SESSION['stored_category_filter']: null;

            //expire date filter
            (isset($_POST['expire_date_filter']) && !empty($_POST['expire_date_filter']))? $filters['expire_date'] = date_format(date_create(Validator::testInput($_POST['expire_date_filter'])), 'Y-m-d') : null;

            //available date filter
            (isset($_POST['available_date_filter']) && !empty($_POST['available_date_filter']))? $filters['available_date'] = date_format(date_create(Validator::testInput($_POST['available_date_filter'])), 'Y-m-d') : null;

            $categories = CategoryManager::getCategoriesList();
            $stored_articles = ArticleManager::getStoredArticlesList(null, null, $filters);

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_' . $navbar . '.php';
            require_once __DIR__ . '/../views/report/stored_articles_manager.php';

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
     * Method that tries to delete the stored article
     */
    public function deleteStoredArticle()
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

            //check if the id is set
            if (isset($_POST['article_id']) && !empty($_POST['article_id'])) {

                $id = intval(Validator::testInput($_POST['article_id']));

                //try to delete article
                if (ArticleManager::deleteArticle($id)) {

                    //reject order requests for this article
                    OrderManager::deleteArticleOrders($id);

                    MessageManager::setSuccessMsg('Articolo eliminata con successo');
                } else {
                    MessageManager::setErrorMsg('Non puoi eliminare questo articolo');
                }
            }
            header('Location: ' . URL . 'report');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;

    }


    /**
     * Method that shows the report
     */
    public function showReport()
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

            $notifications = NotificationManager::getFullNotificationList();

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_' . $navbar . '.php';
            require_once __DIR__ . '/../views/report/report.php';

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

}