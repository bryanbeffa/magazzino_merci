<?php


class ArticleModel
{
    /**
     * attribute that identifies the article name
     */
    private $name;

    /**
     * attribute that identifies the article quantity
     */
    private $quantity;

    /**
     * attribute that identifies the category id
     */
    private $category_id;

    /**
     * attribute that identifies if the article is stored
     */
    private $is_stored;

    /**
     * attribute that identifies the article has been deleted
     */
    private $is_deleted;

    /**
     * attribute that identifies the available date
     */
    private $available_date;


    /**
     * attribute that identifies the expire date
     */
    private $expire_date;


    /**
     * attribute that identifies the user id
     */
    private $user_id;

    public function __construct($name, $quantity, $category_id, $is_stored, $is_deleted, $available_date, $expire_date, $user_id)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
        $this->is_stored = $is_stored;
        $this->is_deleted = $is_deleted;
        $this->available_date = $available_date;
        $this->expire_date = $expire_date;
        $this->user_id = $user_id;
    }

    /**
     * Method that returns the name
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Method that returns the quantity
     */
    public function getQuantity(){
        return $this->quantity;
    }

    /**
     * Method that returns the category id
     */
    public function getCategoryId(){
        return $this->category_id;
    }

    /**
     * Method that returns the is_stored status
     */
    public function isStored(){
        return $this->is_stored;
    }

    /**
     * Method that returns if the article has been deleted
     */
    public function isDeleted(){
        return $this->is_deleted;
    }

    /**
     * Method that returns the available date
     */
    public function getAvailableDate(){
        return $this->available_date;
    }

    /**
     * Method that returns the expire date
     */
    public function getExpireDate(){
        return $this->expire_date;
    }

    /**
     * Method that returns the user id
     */
    public function getUserId(){
        return $this->user_id;
    }
}