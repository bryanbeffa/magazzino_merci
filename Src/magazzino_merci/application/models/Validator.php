<?php


class Validator
{

    /**
     * @param $data value to test
     * @return string if the value is valid
     */
    public static function testInput($data){
        return stripslashes(trim(htmlspecialchars($data)));
    }

    /**
     * @param $value value to test
     * @return false|int if the value is valid
     */
    public static function checkText($value){
        $patt = "/^[\p{L}]+[\p{L}\s\']*$/iu";
        if(strlen($value) == 0 || strlen($value) > 50){
            return false;
        }
        return (preg_match($patt, $value));
    }

    /**
     * @param $email email to test
     * @return false|int if the value is valid
     */
    public static function checkEmail($email){
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * @param $address address
     * @return false|int if the value is valid
     */
    public static function checkAddress($address){
        $patt = "/^[0-9]*[\p{L}]+[\p{L} a-zA-Z0-9\s\']*$/iu";
        if(strlen($address) == 0 || strlen($address) > 50){
            return false;
        }
        return preg_match($patt, $address);
    }

    /**
     * @param $cap city CAP
     * @return false|int if the value is valid
     */
    public static function checkCAP($cap){
        $patt = '/^[0-9]{4,10}$/';
        return preg_match($patt, $cap);
    }

    /**
     * @param $quantity quantity
     * @return false|int if the value is valid
     */
    public static function checkQuantity($quantity){
        $quantity = intval($quantity);
        return ($quantity > 0 && $quantity < 1000);
    }

    /**
     * @param $value value to test
     * @return false|int if the value is valid
     */
    public static function checkPhoneNumber($phone_number){
        $patt = '/^([+]?[ 0-9]+)?(\d{3}|[(]?[0-9]+[)])?([-]?[\s]?[0-9])+$/';
        return preg_match($patt, $phone_number);
    }

    /**
     * Method that returns if the password is complex
     * @param $password password to check
     * @return bool if the password is complex enough
     */
    public static function checkPasswordStrength($password)
    {

        //length 8, 1 uppercase, at least 1 digit
        $pattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/';

        return preg_match($pattern, $password);
    }

    /**
     * Method that checks if the date is valid.
     * @param $date date to check
     * @return bool if the date is valid
     */
    public static function checkDateValidity($date){
        return (date_create($date) > date_create());
    }

    /**
     * Method that returns if the available date is valid
     * @param $expire_date expire date
     * @param $available_date available date
     * @return bool if the available date is valid
     */
    public static function isAvailableDateValid($expire_date, $available_date){
        return (date_create($expire_date) >= date_create($available_date));
    }

    /**
     * Method that returns if the delivery date is valid
     * @param $delivery_date delivery date
     * @param $expire_date expire date
     * @param $available_date available date
     * @return bool if the delivery date is valid
     */
    public static function isDeliveryDateValid($delivery_date, $expire_date, $available_date){
        return (date_create($delivery_date) >= date_create($available_date) && date_create($delivery_date) <= date_create($expire_date));
    }
}