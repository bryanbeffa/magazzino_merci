<?php

class Supplier
{

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/CategoryManager.php';
        require_once __DIR__ . '/../models/CategoryModel.php';
        require_once __DIR__ . '/../models/ArticleManager.php';
        require_once __DIR__ . '/../models/ArticleModel.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = FORNITORI;
    }

    public function index()
    {
        self::showSupplierPage();
    }

    /**
     * Method that shows the supplier page
     */
    public function showSupplierPage()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {

                //get articles not stored list
                $user_id = UserManager::getUserByEmail($_SESSION['email'])['id'];
                $articles = ArticleManager::getSupplierArticlesList();

                require_once __DIR__ . '/../views/global/head.php';
                require_once __DIR__ . '/../views/global/navbar_supplier.php';
                require_once __DIR__ . '/../views/supplier/supplier_manager.php';

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

            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;
    }

    /**
     * Method that shows the supplier updated page
     */
    public function showSupplierUpdatePage($id = null)
    {

        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {
                //check if the id is not null
                if ($id) {

                    $id = Validator::testInput($id);
                    $article = ArticleManager::getArticleById($id);

                    //check if the user has the permission to update the article
                    if ($article['id_utente'] == UserManager::getUserByEmail($_SESSION['email'])['id']) {

                        //get article from db or from session variables
                        if (isset($_SESSION['update_article_name']) &&
                            isset($_SESSION['update_article_quantity']) &&
                            isset($_SESSION['update_article_category_id']) &&
                            isset($_SESSION['update_article_expire_date']) &&
                            isset($_SESSION['update_article_available_date']) &&
                            $id == $_SESSION['update_article_id']
                        ) {

                            //test input
                            $article['nome'] = Validator::testInput($_SESSION['update_article_name']);
                            $article['quantita'] = Validator::testInput($_SESSION['update_article_quantity']);
                            $article['id_categoria'] = Validator::testInput($_SESSION['update_article_category_id']);
                            $article['data_scadenza'] = Validator::testInput($_SESSION['update_article_expire_date']);
                            $article['disponibile_il'] = Validator::testInput($_SESSION['update_article_available_date']);

                        } else {
                            $article = ArticleManager::getArticleById($id);
                        }
                        $_SESSION['update_article_id'] = $id;

                        //get categories list
                        $categories = CategoryManager::getCategoriesList();

                        require_once __DIR__ . '/../views/global/head.php';
                        require_once __DIR__ . '/../views/global/navbar_supplier.php';
                        require_once __DIR__ . '/../views/supplier/supplier_update_page.php';

                        //show error message
                        if (MessageManager::getErrorMsg()) {
                            echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
                            MessageManager::unsetErrorMsg();
                        }
                        exit;
                    }
                }

                //redirect to catalog page
                header('Location: ' . URL . 'supplier/');
                exit;
            }

            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;
    }

    /**
     * Method that updates the article
     */
    public function updateArticle()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {

                $article = ArticleManager::getArticleById($_SESSION['update_article_id']);

                //check if the user has the permission to update the article
                if ($article['id_utente'] == UserManager::getUserByEmail($_SESSION['email'])['id']) {

                    //check post variables and current article id
                    if (isset($_POST['name']) && !empty($_POST['name']) &&
                        isset($_POST['quantity']) && !empty($_POST['quantity']) &&
                        isset($_POST['category_id']) && !empty($_POST['category_id']) &&
                        isset($_POST['expire_date']) && !empty($_POST['expire_date']) &&
                        isset($_POST['available_date']) && !empty($_POST['available_date'])) {

                        //test inputs
                        $name = Validator::testInput($_POST['name']);
                        $quantity = Validator::testInput($_POST['quantity']);
                        $category_id = Validator::testInput($_POST['category_id']);
                        $expire_date = Validator::testInput($_POST['expire_date']);
                        $available_date = Validator::testInput($_POST['available_date']);

                        //session variables
                        $_SESSION['update_article_name'] = $name;
                        $_SESSION['update_article_quantity'] = $quantity;
                        $_SESSION['update_article_category_id'] = $category_id;
                        $_SESSION['update_article_expire_date'] = $expire_date;
                        $_SESSION['update_article_available_date'] = $available_date;

                        //check input fields
                        if (Validator::checkAddress($name)) {
                            if (Validator::checkQuantity($quantity)) {
                                if (CategoryManager::getCategoryById($category_id)) {
                                    if (Validator::checkDateValidity($expire_date)) {
                                        if (Validator::checkDateValidity($available_date)) {
                                            if (Validator::isAvailableDateValid($expire_date, $available_date)) {

                                                $article = new ArticleModel($name, $quantity, $category_id, null, null, $available_date, $expire_date, null);

                                                //try to update article
                                                if (ArticleManager::updateArticle($article, $_SESSION['update_article_id'])) {

                                                    MessageManager::setSuccessMsg('Articolo modificato con successo');
                                                    MessageManager::unsetErrorMsg();

                                                    //delete session variables
                                                    unset($_SESSION['update_article_name']);
                                                    unset($_SESSION['update_article_id']);
                                                    unset($_SESSION['update_article_quantity']);
                                                    unset($_SESSION['update_article_category_id']);
                                                    unset($_SESSION['update_article_expire_date']);
                                                    unset($_SESSION['update_article_available_date']);

                                                    header('Location: ' . URL . 'supplier/');
                                                    exit;
                                                } else {
                                                    MessageManager::setErrorMsg("Impossibile modificare questo articolo");
                                                }
                                            } else {
                                                MessageManager::setErrorMsg("La data di disponibilità non può essere successiva a quella di scadenza");
                                            }
                                        } else {
                                            MessageManager::setErrorMsg("La data di disponibilità deve essere futura");
                                        }
                                    } else {
                                        MessageManager::setErrorMsg("La data di scadenza deve essere futura");
                                    }
                                } else {
                                    MessageManager::setErrorMsg("La categoria inserita non è valida");
                                }
                            } else {
                                MessageManager::setErrorMsg("La quantità deve essere un numero tra 1 e 999");
                            }
                        } else {
                            MessageManager::setErrorMsg("Il nome deve essere composto massimo da 50 caratteri");
                        }

                        header('Location: ' . URL . 'supplier/showSupplierUpdatePage/' . $_SESSION['update_article_id']);
                        exit;
                    }
                }

                //redirect to supplier page
                header('Location: ' . URL . 'supplier/');
                exit;
            }

            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;
    }

    /**
     * Method that tries to delete the article
     */
    public
    function deleteArticle()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {


                //check if the id is set
                if (isset($_POST['articleToDeleteId']) && !empty($_POST['articleToDeleteId'])) {

                    $id = intval(Validator::testInput($_POST['articleToDeleteId']));

                    //get creator user id
                    $article_user_id = ArticleManager::getArticleById($id)['id_utente'];
                    $user_id = UserManager::getUserByEmail($_SESSION['email'])['id'];

                    //check if the article has been insert by the logged user
                    if ($user_id == $article_user_id) {

                        //delete item
                        if (ArticleManager::deleteArticle($id)) {
                            MessageManager::setSuccessMsg('Articolo eliminato con successo');
                        } else {
                            MessageManager::setErrorMsg('Impossibile eliminare questo articolo');
                        }
                    }
                }

                //redirect to supplier page
                header('Location: ' . URL . 'supplier/');
                exit;
            }

            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;
    }

    /**
     * Method that shows the add article page
     */
    public
    static function showArticlesPage()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {

                //get categories list
                $categories = CategoryManager::getCategoriesList();

                require_once __DIR__ . '/../views/global/head.php';
                require_once __DIR__ . '/../views/global/navbar_supplier.php';
                require_once __DIR__ . '/../views/supplier/add_supplier_page.php';

                //show error message
                if (MessageManager::getErrorMsg()) {
                    echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
                    MessageManager::unsetErrorMsg();
                }
                exit;
            }

            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;
    }

    /**
     * Method that tries to add a new articles.
     */
    public
    function addArticle()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == FORNITORE) {

                //check post variables
                if (isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['category_id']) &&
                    isset($_POST['expire_date']) && isset($_POST['available_date']) && !empty($_POST['name']) &&
                    !empty($_POST['quantity']) && !empty($_POST['category_id']) &&
                    !empty($_POST['expire_date']) && !empty($_POST['available_date'])) {

                    //test data inputs
                    $name = Validator::testInput($_POST['name']);
                    $quantity = Validator::testInput($_POST['quantity']);
                    $category_id = Validator::testInput($_POST['category_id']);
                    $expire_date = Validator::testInput($_POST['expire_date']);
                    $available_date = Validator::testInput($_POST['available_date']);


                    //check inputs
                    if (Validator::checkAddress($name)) {
                        if (Validator::checkQuantity($quantity)) {
                            if (CategoryManager::getCategoryById($category_id)) {
                                if (Validator::checkDateValidity($expire_date)) {
                                    if (Validator::checkDateValidity($available_date)) {
                                        if (Validator::isAvailableDateValid($expire_date, $available_date)) {

                                            //try to add article
                                            $article = new ArticleModel($name, $quantity, $category_id, 0, 0, $available_date, $expire_date, intval(UserManager::getUserByEmail($_SESSION['email'])['id']));

                                            if (ArticleManager::addArticle($article)) {

                                                //check if the user inserted an image
                                                if (isset($_FILES['articleImage']) && $_FILES['articleImage']['error'] == UPLOAD_ERR_OK) {
                                                    if (getimagesize($_FILES['articleImage']['tmp_name'])) {
                                                        $tmp_name = $_FILES["articleImage"]["tmp_name"];
                                                        $image_name = 'article' . $_SESSION['last_inserted_article_id'] . '.' . pathinfo($_FILES['articleImage']['name'], PATHINFO_EXTENSION);
                                                        $image_path = __DIR__ . "\..\libs\img\articles/$image_name";
                                                        move_uploaded_file($tmp_name, $image_path);

                                                        //update
                                                        ArticleManager::setArticleImage($_SESSION['last_inserted_article_id'], $image_name);
                                                    }
                                                }

                                                unset($_SESSION['last_inserted_article_id']);

                                                MessageManager::setSuccessMsg('Articolo inserito con successo');
                                                header('Location: ' . URL . 'supplier');
                                                exit;

                                            } else {
                                                MessageManager::setErrorMsg("Impossibile inserire l'articolo");
                                            }
                                        } else {
                                            MessageManager::setErrorMsg("La data di disponibilità non può essere successiva a quella di scadenza");
                                        }
                                    } else {
                                        MessageManager::setErrorMsg("La data di disponibilità deve essere futura");
                                    }
                                } else {
                                    MessageManager::setErrorMsg("La data di scadenza deve essere futura");
                                }
                            } else {
                                MessageManager::setErrorMsg("La categoria inserita non è valida");
                            }
                        } else {
                            MessageManager::setErrorMsg("La quantità deve essere un numero tra 1 e 999");
                        }
                    } else {
                        MessageManager::setErrorMsg("Il nome deve essere composto massimo da 50 caratteri");
                    }

                }
                //show errors
                header('Location: ' . URL . 'supplier/showArticlesPage');
                exit;
            }

            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;
        }

        header('Location: ' . URL . 'home/index');
        exit;
    }
}