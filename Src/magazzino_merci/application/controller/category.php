<?php

class Category
{

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/CategoryManager.php';
        require_once __DIR__ . '/../models/CategoryModel.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = CATEGORIE;
    }

    public function index()
    {
        $this->showCategoriesPage();
    }

    /**
     * Method that shows the add categories page
     */
    public function showCategoriesPage()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {


                //get category list
                $categories = CategoryManager::getCategoriesList();

                require_once __DIR__ . '/../views/global/head.php';
                require_once __DIR__ . '/../views/global/navbar_admin.php';
                require_once __DIR__ . '/../views/category/category_manager.php';

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
     * Method that tries to delete the desired category
     */
    public function deleteCategory()
    {

        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {

                //check if the id is set
                if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {

                    $id = intval(Validator::testInput($_POST['category_id']));

                    if (CategoryManager::getCategoryById($id)) {
                        //try to delete category
                        if (CategoryManager::deleteCategory($id)) {
                            MessageManager::setSuccessMsg('Categoria eliminata con successo');
                        } else {
                            MessageManager::setErrorMsg('Non puoi eliminare una categoria alla quale sono associati degli articoli');
                        }
                    }
                }
                header('Location: ' . URL . 'category');
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
     * Method that shows the add category page
     */
    public function showAddCategoryPage()
    {

        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {

                require_once __DIR__ . '/../views/global/head.php';
                require_once __DIR__ . '/../views/global/navbar_admin.php';
                require_once __DIR__ . '/../views/category/add_category_page.php';

                //show error
                if (MessageManager::getErrorMsg()) {
                    echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
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
     * Method that tries to add a new category
     */
    public function addCategory()
    {

        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {

                //check post variable
                if (isset($_POST['category_name'])) {

                    //test values
                    $name = Validator::testInput($_POST['category_name']);

                    //session variable
                    $_SESSION['new_category_name'] = $name;

                    //get user id
                    $id = UserManager::getUserByEmail($_SESSION['email'])['id'];

                    if (Validator::checkText($name)) {

                        //create category
                        $category = new CategoryModel($name, $id);

                        //try to add category
                        if (CategoryManager::addCategory($category)) {

                            //delete session variables
                            unset($_SESSION['new_category_name']);

                            MessageManager::setSuccessMsg('Categoria creata con successo');
                            MessageManager::unsetErrorMsg();
                            header('Location: ' . URL . 'category');

                        } else {
                            MessageManager::setErrorMsg("Categoria già esistente");
                        }
                    } else {
                        MessageManager::setErrorMsg("La categoria deve essere composta solo da lettere, max 50 caratteri");
                    }
                }
                //reload add category page
                $this->showAddCategoryPage();
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
     * Method that shows the update category page
     * @param $id category id
     */
    public function showUpdateCategoryPage($id = null)
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {
                $id = Validator::testInput($id);
                //check if the id is not null
                if ($id && CategoryManager::getCategoryById(Validator::testInput($id))) {

                    //check if the id has been modified
                    if (isset($_SESSION['update_category_name']) && $_SESSION['update_category_id'] == $id) {

                        //set category data
                        $category = array();
                        $name = Validator::testInput($_SESSION['update_category_name']);

                        //create category with no user
                        $category['nome'] = $name;
                    } else {
                        $category = CategoryManager::getCategoryById($id);
                    }

                    $_SESSION['update_category_id'] = Validator::testInput($id);

                    require_once __DIR__ . '/../views/global/head.php';
                    require_once __DIR__ . '/../views/global/navbar_admin.php';
                    require_once __DIR__ . '/../views/category/update_category_page.php';

                    //show error
                    if (MessageManager::getErrorMsg()) {
                        echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
                    }
                    exit;
                }

                //redirect to category page
                header('Location: ' . URL . 'category/');
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
     * Method that tries to update the category
     */
    public function updateCategory()
    {

        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == ADMIN) {

                //check post variables
                if (isset($_POST['name'])) {

                    //test values
                    $name = Validator::testInput($_POST['name']);

                    //session variables
                    $_SESSION['update_category_name'] = $name;

                    //check every field
                    if (Validator::checkText($name)) {

                        //create category
                        $category = new CategoryModel($name, null);

                        //try to update category
                        if (CategoryManager::updateCategory($category, $_SESSION['update_category_id'])) {

                            //delete session variables
                            unset($_SESSION['update_category_name']);

                            MessageManager::setSuccessMsg('Il nome categoria è stato modificato con successo');
                            MessageManager::unsetErrorMsg();
                            header('Location: ' . URL . 'category');
                            exit;

                        } else {
                            MessageManager::setErrorMsg('Il nome della categoria è già utilizzato');
                        }

                    } else {
                        MessageManager::setErrorMsg("Nome non valido, da 1 a 50 lettere");
                    }

                }

                //reload add user page
                header('Location: ' . URL . 'category/showUpdateCategoryPage/' . $_SESSION['update_category_id']);
                exit;
            }
            //redirect to catalog page
            header('Location: ' . URL . 'catalog/');
            exit;

        }
        header('Location: ' . URL . 'home ');
        exit;
    }
}