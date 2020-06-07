<?php


class MessageManager
{

    /**
     * Method that sets the error message
     * @param $error_msg error message
     */
    public static function setErrorMsg($error_msg){
        $_SESSION['error_msg'] = $error_msg;
    }

    /**
     * Method that unset the error message
     */
    public static function unsetErrorMsg(){
        $_SESSION['error_msg'] = null;
    }

    /**
     * Method that returns the error message
     * return error message
     */
    public static function getErrorMsg(){
        return (isset($_SESSION['error_msg']))? $_SESSION['error_msg']: null;
    }

    /**
     * Method that sets the success message
     * @param $success_msg success message
     */
    public static function setSuccessMsg($success_msg){
        $_SESSION['success_msg'] = $success_msg;
    }

    /**
     * Method that unset the success message
     */
    public static function unsetSuccessMsg(){
        $_SESSION['success_msg'] = null;
    }

    /**
     * Method that returns the success error
     * return error message
     */
    public static function getSuccessMsg(){
        return (isset($_SESSION['success_msg']))? $_SESSION['success_msg']: null;
    }

}