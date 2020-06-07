<?php
/**
 * Created by PhpStorm.
 * UserModel: Bryan
 * Date: 13.09.2019
 * Time: 15:23
 */

class UserModel
{
    /**
     * attribute that identifies the user name
     */
    private $name;

    /**
     * attribute that identifies the user surname
     */
    private $surname;

    /**
     * attribute that identifies the user email
     */
    private $email;

    /**
     * attribute that identifies the user password
     */
    private $password;

    /**
     * attribute that identifies the user permission
     */
    private $permission;

    /**
     * attribute that identifies the user address
     */
    private $address;

    /**
     * attribute that identifies the user city
     */
    private $city;

    /**
     * attribute that identifies the CAP
     */
    private $cap;

    /**
     * attribute that identifies the user phone number
     */
    private $phone_number;

    /**
     * UserModel constructor.
     * @param string $name name
     * @param string $surname surname
     * @param string $email email
     * @param $password password
     * @param $permission permission
     * @param $address address
     * @param $city city
     * @param $cap cap
     * @param $phone_number phone number
     */
    public function __construct(string $name, string $surname, string $email,  $password,  $permission, $address, $city, $cap, $phone_number)
    {
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->address = $address;
        $this->city = $city;
        $this->cap = $cap;
        $this->phone_number = $phone_number;
        $this->permission = $permission;
        $this->password = $password;
    }

    /**
     * Method that returns the password
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Method that returns the name
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Method that returns the surname
     */
    public function getSurname(){
        return $this->surname;
    }

    /**
     * Method that returns the email
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Method that returns the permission
     */
    public function getPermission(){
        return $this->permission;
    }

    /**
     * Method that returns the address
     */
    public function getAddress(){
        return $this->address;
    }

    /**
     * Method that returns the city
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * Method that returns the CAP
     */
    public function getCap(){
        return $this->cap;
    }

    /**
     * Method that returns the phone number
     */
    public function getPhoneNumber(){
        return $this->phone_number;
    }
}