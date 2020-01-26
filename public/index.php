<?php
/**
 * some initial configuaration
 */
ignore_user_abort(false); // aborts if the user connection is closed/aborted.
set_time_limit(30); // limit the maximum execution time.
date_default_timezone_set('UTC'); // ensuring the php time is UTC.


session_start(); 
if( !$_SESSION['loggedin'] ) $_SESSION['loggedin'] = false; 

/**
 * adding composer autoloader
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * ensuring that the database is closed at the end :)
 */
function shutdown(){
    \App\Database::disconnect();
}
register_shutdown_function('shutdown');

/**
 * Error/Exception Handling
 */
error_reporting(E_ALL);
$error = new App\Error();
set_error_handler( [ $error, 'errorHandler' ] );
set_exception_handler( [ $error, 'exceptionHandler' ] );

App\Router::init();
App\Router::execute();
