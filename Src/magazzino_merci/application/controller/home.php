<?php

class Home
{

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/MessageManager.php';
        require_once __DIR__ . '/../config/config.php';
    }

    public function index()
    {
        require_once __DIR__ . '/../views/global/head.php';
        require_once __DIR__ . '/../views/login/index.php';

        //show error
        if (MessageManager::getErrorMsg()) {
            echo "<script>$.notify('" . MessageManager::getErrorMsg() . "', 'error');</script>";
            MessageManager::unsetErrorMsg();
        }
        exit;

    }

    public function login()
    {

        //check if the post variables are set
        if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {

            //check variables content
            $email = Validator::testInput($_POST['email']);
            $password = Validator::testInput($_POST['password']);

            //check credentials
            if (UserManager::checkCredentials($email, $password)) {

                //set session variables
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;

                MessageManager::setSuccessMsg('Ciao ' . UserManager::getUserByEmail($_SESSION['email'])['nome'] . ', ti diamo il benvenuto');
                MessageManager::unsetErrorMsg();

                header('Location: ' . URL . 'catalog');
                exit;
            }

            //wrong credentials
            MessageManager::setErrorMsg('Le credenziali inserite non sono corrette');
        }
        header('Location: ' . URL . 'home');
    }


}