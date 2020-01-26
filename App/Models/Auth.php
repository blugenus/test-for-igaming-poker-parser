<?php
namespace App\Models;

class Auth extends \App\Model {

    /**
     * Verify username and password
     * 
     * @param string $user  The user name the user poster
     * @param string $password  The password the user posted
     *
     * @return void
     */
    public static function login( $user, $password ){
        $result = static::bindAndQuery( 
            'SELECT `userId`, `password` FROM `users` WHERE `username` = ?;', 
            's', 
            [ &$user ] 
        );
        // if the username is in our database
        if( sizeof( $result['records'] ) == 1 ){
            if( password_verify( $password, $result['records'][0]['password'] ) ){
                // set the session vaiables
                $_SESSION['loggedin'] = true; 
                $_SESSION['userId'] = $result['records'][0]['userId']; 
                $_SESSION['username'] = $_POST['uname'];
                return true;
            }
        } 

        return false;
    }

    /**
     * Logout current user
     * 
     * @return void
     */
    public static function logout(){
        $_SESSION['loggedin'] = false; 
        $_SESSION['userId'] = 0;
        $_SESSION['username'] = ''; 
    }

    /**
     * Return the currently Logged in user Id
     * 
     * @return int
     */
    public static function getCurrentUser(){
        return $_SESSION['userId'];
    }

}