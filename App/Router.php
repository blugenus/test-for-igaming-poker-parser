<?php

namespace App;

/**
 * Router Class - handles the application's routes
 */
class Router {

    private static $dispatcher;

    /**
     * Initialize the router's routes.
     * 
     * @return void
     */
    public static function init(){
        static::$dispatcher = \FastRoute\simpleDispatcher( function( \FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/login', [ 'controller' => 'Login', 'function' => 'showLoginPage', 'auth' => false ] );
            $r->addRoute('POST', '/login', [ 'controller' => 'Login', 'function' => 'userlogIn', 'auth' => false ] );
            $r->addRoute('GET', '/logout', [ 'controller' => 'Login', 'function' => 'showLogOutPage', 'auth' => false  ] );

            $r->addRoute('GET', '/upload', [ 'controller' => 'Hands', 'function' => 'showUploadPage', 'auth' => true ] );
            $r->addRoute('POST', '/upload', [ 'controller' => 'Hands', 'function' => 'processHandsFile', 'auth' => true ] );
        });
    }

    /**
     * Run the router.
     *
     * for more detailed information on the router itself please visit
     * https://github.com/nikic/FastRoute
     * 
     * @return void
     */
    public static function execute(){
        $uri = $_SERVER['REQUEST_URI'];
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = static::$dispatcher->dispatch( $_SERVER['REQUEST_METHOD'], $uri );
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                if( $uri == '' || $uri == '/' ){ // setting a default page
                    header( 'Location: /upload', true, 302 );
                } else { 
                    throw new \Exception('No route matched.', 404);
                }
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                throw new \Exception('Route matched but method not allowed', 405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                if ( (session_id() == '' || !isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] ) && $handler['auth'] ) { 
                    header( 'Location: /login', true, 302 );
                } else {
                    $controller = 'App\Controllers\\' . $handler['controller'];
                    $function = $handler['function'];
                    (new $controller())->$function($vars);
                }
                break;
        }
    }

}