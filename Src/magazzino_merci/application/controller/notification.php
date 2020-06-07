<?php

class Notification
{

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserManager.php';
        require_once __DIR__ . '/../models/ArticleManager.php';
        require_once __DIR__ . '/../models/Validator.php';
        require_once __DIR__ . '/../models/NotificationManager.php';
        require_once __DIR__ . '/../models/OrderManager.php';
        require_once __DIR__ . '/../models/OperationTypeManager.php';
        require_once __DIR__ . '/../config/config.php';

        $_SESSION['nav_bar_item'] = NOTIFICHE;
    }

    public function index()
    {
        $this->getAllNotifications();
    }

    /**
     * Method that returns user notifications in json format
     */
    public function getNotifications()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == BASE) {

                //get user notification
                $user_id = Validator::testInput(UserManager::getUserByEmail($_SESSION['email'])['id']);

                $user_notifications = NotificationManager::getUserNotificationList($user_id, NUM_NOTIFICATIONS);
                $notifications = array();

                foreach ($user_notifications as $notification) {

                    $article = ArticleManager::getArticleById($notification['id_articolo'])['nome'];
                    $notification = array_merge($notification, array('nome_articolo' => $article));
                    array_push($notifications, $notification);
                }

                echo json_encode($notifications);
                exit;
            }
        }
    }

    /**
     * Method that returns the user notifications
     */
    public function getAllNotifications()
    {
        //check if the user is logged and user permission
        if (UserManager::isUserLogged()) {
            if (UserManager::getPermission($_SESSION['email']) == BASE) {

                //get user notification
                $user_id = Validator::testInput(UserManager::getUserByEmail($_SESSION['email'])['id']);
                $notifications = NotificationManager::getUserNotificationList($user_id);

                require_once __DIR__ . '/../views/global/head.php';
                require_once __DIR__ . '/../views/global/navbar_base.php';
                require_once __DIR__ . '/../views/notification/user_notifications.php';

                exit;
            }

            header('Location: ' . URL . 'catalog');
            exit;

        }
        header('Location: ' . URL . 'home');
    }
}