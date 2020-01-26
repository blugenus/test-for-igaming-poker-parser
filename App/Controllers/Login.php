<?php
namespace App\Controllers;

use App\Models\Auth;

/**
 * Login controller - handles authentifiacion requests
 */
class Login extends \App\Controller {

    /**
     * * Send the login page to the browser
     * 
     * @return void
     */
    public function showLoginPage(){
        static::render( 'login.html' );
    }

    /**
     * Query using Binding. 
     * 
     * @param string $sql  The SQL statement we want to query
     *
     * @return void
     */
    public function userlogIn(){ // process provided account
        if( Auth::login( $_POST['uname'], $_POST['psw'] ) ){
            header( 'Location: /upload', true, 302 );
        } else {
            static::render( 'login.html', [ 'message' => 'Invalid username or password' ] );
        }
    }

    /**
     * Logs out the user sends the logout page to the browser
     *
     * @return void
     */
    public function showLogOutPage(){
        Auth::logout();
        static::render( 'logout.html' );
    }

}