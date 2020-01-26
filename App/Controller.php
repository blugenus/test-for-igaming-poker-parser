<?php

namespace App;

/*
 * Base Controller
 */
abstract class Controller {

    /**
     * Loads the template file specified, applies the data to it and send it to the browser.
     *
     * for more detailed information on the templating engine itself please visit
     * https://twig.symfony.com/
     * 
     * @return void
     */
    function render( $templateFile, $data = [], $httpResponseCode = 200 ){
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/Views/'); // initializing twig's file loader
        $twig = new \Twig\Environment( $loader, [] ); // Initializing twig

        http_response_code($httpResponseCode); 
        echo $twig->render( $templateFile, $data ); // outputing the outcome of the template file with the information stored in $data 
    }

}