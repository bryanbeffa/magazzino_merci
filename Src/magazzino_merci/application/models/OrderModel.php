<?php

class OrderModel
{
    /**
     * attribute that identifies the user id
     */
    private $user_id;

    /**
     * attribute that identifies the article id
     */
    private $article_id;

    /**
     * attribute that identifies the article quantity
     */
    private $quantity;

    /**
     * Attribute that identifies the user delivery date
     */
    private $delivery_date;

    /**
     * OrderModel constructor.
     * @param $quantity article quantity
     * @param $article_id article id
     * @param $user_id user id
     */
    public function __construct($quantity, $article_id,  $user_id, $delivery_date)
    {
        $this->quantity = $quantity;
        $this->article_id = $article_id;
        $this->user_id = $user_id;
        $this->delivery_date = $delivery_date;
    }

    /**
     * Method that returns the article quantity
     */
    public function getQuantity(){
        return $this->quantity;
    }

    /**
     * Method that returns the user id
     */
    public function getUserId(){
        return $this->user_id;
    }

    /**
     * Method that returns the article id
     */
    public function getArticleId(){
        return $this->article_id;
    }

    /**
     * Method that returns the delivery date
     */
    public function getDeliveryDate(){
        return $this->delivery_date;
    }
}