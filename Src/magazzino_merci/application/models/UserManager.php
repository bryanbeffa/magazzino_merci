<?php
/**
 * Created by PhpStorm.
 * UserModel: Bryan
 * Date: 13.09.2019
 * Time: 15:58
 */

class UserManager
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
     * Method that returns if the user credentials are correct
     */
    public static function checkCredentials(string $email, string $password)
    {
        self::getConnection();

        //check if the email exists
        if (self::isExistingEmail($email)) {

            //check if the password is correct
            return self::isPasswordCorrect($email, $password);
        }

        return false;

    }

    /**
     * Method that return if the email is in the system.
     */
    private static function isExistingEmail(string $email)
    {
        //get the number of rows that contain the user email (MAX 1)
        self::getConnection();
        $prepared_query = self::$conn->prepare("SELECT count(*) FROM utenti WHERE email = :email AND eliminato = 0");
        $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
        $prepared_query->execute();
        $res = $prepared_query->fetch();

        //check if the row count is 1
        return intval($res[0]) == 1;
    }

    /**
     * Method that return if the password is correct.
     */
    private static function isPasswordCorrect(string $email, string $password)
    {
        //get the password from database
        self::getConnection();
        $prepared_query = self::$conn->prepare("SELECT password FROM utenti WHERE email = :email");
        $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
        $prepared_query->execute();
        $res = $prepared_query->fetch();

        $hashed_password = $res[0];

        //check if the inserted password is correct
        return password_verify($password, $hashed_password);
    }

    /**
     * Method that returns if the user is logged
     */
    public static function isUserLogged()
    {
        //check if the variables are initialized
        if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
            return self::checkCredentials($_SESSION['email'], $_SESSION['password']);
        }
        return false;
    }

    /**
     * Method that unset session variables
     */
    public static function logout()
    {
        session_destroy();
    }

    /**
     * Method that returns the user permission.
     */
    public static function getPermission(string $email)
    {
        try {
            //get the password from database
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT id_permesso FROM utenti WHERE email = :email");
            $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
            $prepared_query->execute();
            $res = $prepared_query->fetch();

            return $res[0];
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that returns if the user has been deleted.
     * @param $id user id
     * @return bool if the user has been deleted
     */
    public
    static function isUserDeleted($id)
    {
        try {

            //get the password from database
            self::getConnection();
            $prepared_query = self::$conn->prepare("SELECT eliminato FROM utenti WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();
            $res = $prepared_query->fetch();

            return $res[0];
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that returns the list of users
     */
    public
    static function getUsersList($deleted)
    {
        try {
            //get user list
            self::getConnection();

            $deleted = intval($deleted);

            $prepared_query = self::$conn->prepare("SELECT * FROM utenti WHERE eliminato = :eliminato");
            $prepared_query->bindParam(':eliminato', $deleted, PDO::PARAM_INT);
            $prepared_query->execute();
            $users = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

            return $users;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that recoveries the deleted user
     * @param $id user id
     * @return bool if the operation was successful
     */
    public
    static function recoveryUser($id)
    {
        try {
            //get user list
            self::getConnection();

            $prepared_query = self::$conn->prepare("UPDATE utenti set eliminato = 0 WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that tries to delete the desired user.
     * @param $id UserModel id
     * @return int number of rows affected
     */
    public
    static function deleteUser($id)
    {
        try {
            // delete user
            self::getConnection();
            $prepared_query = self::$conn->prepare("UPDATE utenti set eliminato = 1 WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return intval($prepared_query->rowCount());

        } catch (PDOException $ex) {
            exit($ex);
            return 0;
        }
    }

    /**
     * Method that tries to add the user.
     * @param $user user to add
     * @return bool if the operation was successful
     */
    public
    static function addUser($user)
    {
        try {
            self::getConnection();
            require_once __DIR__ . '/UserModel.php';

            //get params
            $email = $user->getEmail();
            $name = $user->getName();
            $surname = $user->getSurname();
            $address = $user->getAddress();
            $city = $user->getCity();
            $cap = $user->getCap();
            $phone_number = $user->getPhoneNumber();
            $permission = $user->getPermission();
            $password = $user->getPassword();

            //add user
            $prepared_query = self::$conn->prepare("INSERT INTO utenti (email, nome, cognome, via, citta, cap, telefono, id_permesso, password) 
                        values(:email, :nome, :cognome, :via, :citta, :cap, :telefono, :id_permesso, :password)");

            //bind params
            $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
            $prepared_query->bindParam(':nome', $name, PDO::PARAM_STR);
            $prepared_query->bindParam(':cognome', $surname, PDO::PARAM_STR);
            $prepared_query->bindParam(':via', $address, PDO::PARAM_STR);
            $prepared_query->bindParam(':citta', $city, PDO::PARAM_STR);
            $prepared_query->bindParam(':cap', $cap, PDO::PARAM_INT);
            $prepared_query->bindParam(':telefono', $phone_number, PDO::PARAM_STR);
            $prepared_query->bindParam(':id_permesso', $permission, PDO::PARAM_INT);
            $prepared_query->bindParam(':password', $password, PDO::PARAM_STR);

            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Get user by email.
     * @param $email user email
     * @return the desired user or null
     */
    public
    static function getUserByEmail($email)
    {
        try {
            self::getConnection();

            //prepare query
            $prepared_query = self::$conn->prepare("SELECT * FROM utenti WHERE EMAIL = :email");
            $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
            $prepared_query->execute();

            return $prepared_query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Get user by id.
     * @param $id user id
     * @return the desired user or null
     */
    public
    static function getUserById($id)
    {
        try {
            self::getConnection();

            //prepare query
            $prepared_query = self::$conn->prepare("SELECT * FROM utenti WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return $prepared_query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            return null;
        }
    }

    /**
     * Method that tries to update the desired user.
     * @param $user user
     * @param $id user id
     * @return bool if the operation was successful
     */
    public
    static function updateUser($user, $id)
    {
        try {
            self::getConnection();

            $sql = "UPDATE utenti set email = :email, nome = :nome, cognome = :cognome, via = :via, citta = :citta, cap = :cap, telefono = :telefono, id_permesso = :id_permesso";
            $password = $user->getPassword();

            //check if update password
            if (!empty($password)) {
                $sql .= ", password  = :password";
            }

            $sql .= " WHERE ID = :id";

            //prepare query
            $prepared_query = self::$conn->prepare($sql);

            //get params
            $email = $user->getEmail();
            $name = $user->getName();
            $surname = $user->getSurname();
            $address = $user->getAddress();
            $city = $user->getCity();
            $cap = $user->getCap();
            $phone_number = $user->getPhoneNumber();
            $permission = $user->getPermission();

            if (!empty($password)) {
                $prepared_query->bindParam(':password', $password, PDO::PARAM_STR);
            }

            //bind params
            $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
            $prepared_query->bindParam(':nome', $name, PDO::PARAM_STR);
            $prepared_query->bindParam(':cognome', $surname, PDO::PARAM_STR);
            $prepared_query->bindParam(':via', $address, PDO::PARAM_STR);
            $prepared_query->bindParam(':citta', $city, PDO::PARAM_STR);
            $prepared_query->bindParam(':cap', $cap, PDO::PARAM_INT);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->bindParam(':telefono', $phone_number, PDO::PARAM_STR);
            $prepared_query->bindParam(':id_permesso', $permission, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }
}