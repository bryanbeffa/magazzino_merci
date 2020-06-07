<?php

class User
{

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/UserModel.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = UTENTI;
    }

    public function index()
    {
        $this->showUsersPage();
    }

    /**
     * Method that shows the users list
     */
    public function showUsersPage()
    {

        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {

            //get user list
            $users = UserManager::getUsersList(false);

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_admin.php';
            require_once __DIR__ . '/../views/user/user_manager.php';

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

        header('Location: ' . URL . 'home/index');
    }

    /**
     * Method that shows the list of deleted users
     */
    public function usersRecoveryPage()
    {

        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {

            //get user list
            $users = UserManager::getUsersList(true);

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_admin.php';
            require_once __DIR__ . '/../views/user/user_recovery_page.php';

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

        header('Location: ' . URL . 'home/index');
    }

    /**
     * Method that recoveries the user account
     */
    public function recoveryUser($user_id = null)
    {
        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {

            $id = Validator::testInput($user_id);

            //check if the id is not null
            if ($id) {
                $users = UserManager::getUsersList(true);
                $user_exists = false;

                //check if the id is valid
                foreach ($users as $user) {
                    if ($user['id'] == $id) {
                        $user_exists = true;
                        break;
                    }
                }

                if ($user_exists) {
                    //try to recovery user
                    if (UserManager::recoveryUser($user_id)) {
                        MessageManager::setSuccessMsg('Utente recuperato con successo');
                        header('Location: ' . URL . 'user/usersRecoveryPage');
                        exit;
                    }

                    MessageManager::setErrorMsg('Impossibile recuperare questo account');

                }
            }
            header('Location: ' . URL . 'user/usersRecoveryPage');
            exit;
        }

        header('Location: ' . URL . 'home/index');

    }

    /**
     * Method that shows the add user page
     */
    public function showAddUserPage()
    {

        //check user permission
        if ($this->isUserAdmin()) {

            require_once __DIR__ . '/../views/global/head.php';
            require_once __DIR__ . '/../views/global/navbar_admin.php';
            require_once __DIR__ . '/../views/user/add_user_page.php';

            //show error
            if (MessageManager::getErrorMsg()) {
                echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
            }

            exit;
        }

        header('Location: ' . URL . 'home/index');
    }

    /**
     * Method that tries to delete the desired user
     */
    public function deleteUser()
    {

        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {

            //check if the id is set
            if (isset($_POST['userToDeleteId']) && !empty($_POST['userToDeleteId'])) {

                $id = intval(Validator::testInput($_POST['userToDeleteId']));

                //check if the user is trying to delete his account
                if ($id != intval(UserManager::getUserByEmail($_SESSION['email'])['id'])) {

                    //try to delete user
                    if (UserManager::deleteUser($id) == 1) {
                        MessageManager::setSuccessMsg('Utente eliminato con successo');
                    } else {
                        MessageManager::setErrorMsg('Impossibile eliminare questo utente');
                    }
                } else {
                    MessageManager::setErrorMsg('Non puoi eliminare il tuo account');
                }
            }
            header('Location: ' . URL . 'user');
            exit;
        }
        header('Location: ' . URL . 'home/index');
    }

    /**
     * Method that disconnects the user
     */
    public function logout()
    {
        //logout
        UserManager::logout();
        header('Location: ' . URL . 'home ');
    }

    /**
     * Method that returns if the user is admin
     * @return bool if the user has admin privileges
     */
    private function isUserAdmin()
    {
        //check if the user is logged and user permission
        return (UserManager::isUserLogged() && UserManager::getPermission($_SESSION['email']) == ADMIN);
    }

    /**
     * Method that shows the update users page
     * @param $id UserModel id
     */
    public function updateUserPage($id = null)
    {

        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {


            //check if the id is not null
            if ($id) {
                if (UserManager::getUserById($id) && !UserManager::isUserDeleted(Validator::testInput($id))) {
                    $id = Validator::testInput($id);

                    //get user from db or from session variables
                    if (isset($_SESSION['update_user_email']) && isset($_SESSION['update_user_name']) && isset($_SESSION['update_user_surname']) &&
                        isset($_SESSION['update_user_address']) && isset($_SESSION['update_user_city']) && isset($_SESSION['update_user_cap']) &&
                        isset($_SESSION['update_user_phone_number']) && isset($_SESSION['update_user_permission']) && $id == $_SESSION['update_user_id']) {

                        //set user data
                        $email = Validator::testInput($_SESSION['update_user_email']);
                        $name = Validator::testInput($_SESSION['update_user_name']);
                        $surname = Validator::testInput($_SESSION['update_user_surname']);
                        $address = Validator::testInput($_SESSION['update_user_address']);
                        $city = Validator::testInput($_SESSION['update_user_city']);
                        $cap = Validator::testInput($_SESSION['update_user_cap']);
                        $phone_number = Validator::testInput($_SESSION['update_user_phone_number']);
                        $permission = Validator::testInput($_SESSION['update_user_permission']);

                        $user['nome'] = $name;
                        $user['cognome'] = $surname;
                        $user['email'] = $email;
                        $user['id_permesso'] = $permission;
                        $user['via'] = $address;
                        $user['citta'] = $city;
                        $user['cap'] = $cap;
                        $user['telefono'] = $phone_number;

                    } else {
                        $user = UserManager::getUserById($id);
                    }

                    $_SESSION['update_user_id'] = $id;

                    require_once __DIR__ . '/../views/global/head.php';
                    require_once __DIR__ . '/../views/global/navbar_admin.php';
                    require_once __DIR__ . '/../views/user/update_user_page.php';

                    //show error
                    if (MessageManager::getErrorMsg()) {
                        echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
                    }
                    exit;
                }
            }

            header('Location: ' . URL . 'user ');
            exit;
        }

        header('Location: ' . URL . 'home ');
    }

    /**
     * Method that tries to add a new user
     */
    public
    function addUser()
    {

        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {

            //check post variables
            if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['surname']) &&
                isset($_POST['address']) && isset($_POST['city']) && isset($_POST['cap']) &&
                isset($_POST['phone_number']) && isset($_POST['permission']) && isset($_POST['password']) &&
                isset($_POST['confirm_password']) && !empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['surname']) &&
                !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['cap']) &&
                !empty($_POST['phone_number']) && !empty($_POST['permission']) && !empty($_POST['password']) &&
                !empty($_POST['confirm_password'])) {

                //test values
                $email = Validator::testInput($_POST['email']);
                $name = Validator::testInput($_POST['name']);
                $surname = Validator::testInput($_POST['surname']);
                $address = Validator::testInput($_POST['address']);
                $city = Validator::testInput($_POST['city']);
                $cap = Validator::testInput($_POST['cap']);
                $phone_number = Validator::testInput($_POST['phone_number']);
                $permission = Validator::testInput($_POST['permission']);
                $password = Validator::testInput($_POST['password']);
                $confirm_password = Validator::testInput($_POST['confirm_password']);

                //session variables
                $_SESSION['new_user_email'] = $email;
                $_SESSION['new_user_name'] = $name;
                $_SESSION['new_user_surname'] = $surname;
                $_SESSION['new_user_address'] = $address;
                $_SESSION['new_user_city'] = $city;
                $_SESSION['new_user_cap'] = $cap;
                $_SESSION['new_user_phone_number'] = $phone_number;
                $_SESSION['new_user_permission'] = $permission;

                //check if the passwords match
                if ($password === $confirm_password) {

                    //check every field
                    if (Validator::checkEmail($email)) {
                        if (Validator::checkText($name)) {
                            if (Validator::checkText($surname)) {
                                if (Validator::checkAddress($address)) {
                                    if (Validator::checkText($city)) {
                                        if (Validator::checkCAP($cap)) {
                                            if (Validator::checkPhoneNumber($phone_number)) {
                                                if (Validator::checkPasswordStrength($password)) {

                                                    //create user
                                                    $user = new UserModel($name, $surname, $email, password_hash($password, PASSWORD_DEFAULT), $permission, $address, $city, $cap, $phone_number);

                                                    //try to add user
                                                    if (UserManager::addUser($user)) {

                                                        //delete session variables
                                                        unset($_SESSION['new_user_email']);
                                                        unset($_SESSION['new_user_name']);
                                                        unset($_SESSION['new_user_surname']);
                                                        unset($_SESSION['new_user_address']);
                                                        unset($_SESSION['new_user_city']);
                                                        unset($_SESSION['new_user_cap']);
                                                        unset($_SESSION['new_user_phone_number']);
                                                        unset($_SESSION['new_user_permission']);

                                                        MessageManager::setSuccessMsg('Utente creato con successo');
                                                        MessageManager::unsetErrorMsg();
                                                        header('Location: ' . URL . 'user');

                                                    } else {
                                                        MessageManager::setErrorMsg("Email già utilizzata");
                                                    }
                                                } else {
                                                    MessageManager::setErrorMsg("La password deve contenere una maiuscola, un numero, almeno 8 caratteri");
                                                }
                                            } else {
                                                MessageManager::setErrorMsg("Numero di telefono non valido, es. +41 0800 800 08 80 (prefisso facoltativo)");
                                            }
                                        } else {
                                            MessageManager::setErrorMsg("CAP non valido, minimo 4 cifre");
                                        }
                                    } else {
                                        MessageManager::setErrorMsg("Città non valida, da 1 a 50 lettere");
                                    }
                                } else {
                                    MessageManager::setErrorMsg("Via non valida, da 1 a 50 caratteri");
                                }
                            } else {
                                MessageManager::setErrorMsg("Cognome non valido, da 1 a 50 lettere");
                            }
                        } else {
                            MessageManager::setErrorMsg("Nome non valido, da 1 a 50 lettere");
                        }
                    } else {
                        MessageManager::setErrorMsg("Email non valida");
                    }
                } else {
                    MessageManager::setErrorMsg("Le password non corrispondono");
                }
            }

            //reload add user page
            $this->showAddUserPage();
            exit;
        }

        header('Location: ' . URL . 'home ');
    }

    /**
     * Method that tries to update the user
     */
    public
    function updateUser()
    {

        //check if the user is logged and user permission
        if ($this->isUserAdmin()) {

            //check post variables
            if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['surname']) &&
                isset($_POST['address']) && isset($_POST['city']) && isset($_POST['cap']) &&
                isset($_POST['phone_number']) && isset($_POST['permission']) && isset($_POST['password'])) {

                //test values
                $email = Validator::testInput($_POST['email']);
                $name = Validator::testInput($_POST['name']);
                $surname = Validator::testInput($_POST['surname']);
                $address = Validator::testInput($_POST['address']);
                $city = Validator::testInput($_POST['city']);
                $cap = Validator::testInput($_POST['cap']);
                $phone_number = Validator::testInput($_POST['phone_number']);
                $permission = Validator::testInput($_POST['permission']);
                $password = Validator::testInput($_POST['password']);

                //session variables
                $_SESSION['update_user_email'] = $email;
                $_SESSION['update_user_name'] = $name;
                $_SESSION['update_user_surname'] = $surname;
                $_SESSION['update_user_address'] = $address;
                $_SESSION['update_user_city'] = $city;
                $_SESSION['update_user_cap'] = $cap;
                $_SESSION['update_user_phone_number'] = $phone_number;
                $_SESSION['update_user_permission'] = $permission;

                //check every field
                if (Validator::checkEmail($email)) {
                    if (Validator::checkText($name)) {
                        if (Validator::checkText($surname)) {
                            if (Validator::checkAddress($address)) {
                                if (Validator::checkText($city)) {
                                    if (Validator::checkCAP($cap)) {
                                        if (Validator::checkPhoneNumber($phone_number)) {
                                            if (strlen($password) > 0 && !Validator::checkPasswordStrength($password)) {

                                                MessageManager::setErrorMsg("La password deve contenere una maiuscola, un numero, almeno 8 caratteri");

                                                //reload add user page
                                                header('Location: ' . URL . 'user/updateUserPage/' . $_SESSION['update_user_id']);
                                                exit;
                                            }

                                            if ($password) {
                                                $password = password_hash($password, PASSWORD_DEFAULT);
                                            }

                                            //create user
                                            $user = new UserModel($name, $surname, $email, $password, $permission, $address, $city, $cap, $phone_number);

                                            //try to update user
                                            if (UserManager::updateUser($user, $_SESSION['update_user_id'])) {

                                                //delete session variables
                                                unset($_SESSION['update_user_email']);
                                                unset($_SESSION['update_user_name']);
                                                unset($_SESSION['update_user_surname']);
                                                unset($_SESSION['update_user_address']);
                                                unset($_SESSION['update_user_city']);
                                                unset($_SESSION['update_user_cap']);
                                                unset($_SESSION['update_user_phone_number']);
                                                unset($_SESSION['update_user_permission']);

                                                MessageManager::setSuccessMsg('Utente modificato con successo');
                                                MessageManager::unsetErrorMsg();
                                                header('Location: ' . URL . 'user');
                                                exit;

                                            } else {
                                                MessageManager::setErrorMsg('Email già utilizzata');
                                            }
                                        } else {
                                            MessageManager::setErrorMsg("Numero di telefono non valido, es. +41 0800 800 08 80 (prefisso facoltativo)");
                                        }
                                    } else {
                                        MessageManager::setErrorMsg("CAP non valido, minimo 4 cifre");
                                    }
                                } else {
                                    MessageManager::setErrorMsg("Città non valida, da 1 a 50 lettere");
                                }
                            } else {
                                MessageManager::setErrorMsg("Via non valida, da 1 a 50 caratteri");
                            }
                        } else {
                            MessageManager::setErrorMsg("Cognome non valido, da 1 a 50 lettere");
                        }
                    } else {
                        MessageManager::setErrorMsg("Nome non valido, da 1 a 50 lettere");
                    }
                } else {
                    MessageManager::setErrorMsg("Email non valida");
                }
            }

            //reload add user page
            header('Location: ' . URL . 'user/updateUserPage/' . $_SESSION['update_user_id']);
            exit;
        }

        header('Location: ' . URL . 'home ');
    }

}