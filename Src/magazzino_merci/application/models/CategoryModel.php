<?php
/**
 * Created by PhpStorm.
 * UserModel: Bryan
 * Date: 13.09.2019
 * Time: 15:23
 */

class CategoryModel
{
    /**
     * attribute that identifies the user name
     */

    private $name;
    /**
     * attribute that identifies the id of the user who created the category
     */
    private $user_id;

    public function __construct(string $name, $user_id)
    {
        $this->name = $name;
        $this->user_id = $user_id;
    }

    /**
     * Method that returns the name
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Method that returns the user id
     */
    public function getUserId(){
        return $this->user_id;
    }
}