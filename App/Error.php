<?php

namespace App;

class Error extends Controller{

    /**
     * Error handler. Handles errors by throwing an ErrorException.
     *
     * @param int $errno  Error level
     * @param string $errstr  Error message
     * @param string $errfile  Filename the error was raised in
     * @param int $errline  Line number in the file
     *
     * @return void
     */
    public function errorHandler( $errno, $errstr, $errfile, $errline ) {
        if ( error_reporting() !== 0 ) {  // to keep the @ operator working
            throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline);
        }
    }

    /**
     * Exception handler. Outputs an html page reporting the issue.
     *
     * @param Exception $exception  The exception details
     *
     * @return void
     */
    public function exceptionHandler($exception){
        $code = $exception->getCode(); // Code is 404 (not found) or 500 (general error)

        $data = [
            'title' => '',
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];

        switch( $code ){
            case 400:
                $data['title'] = 'Bad Request';
                static::render( '4xx.html', $data, $code );
                break;
            case 401:
                $data['title'] = 'Unauthorised';
                static::render( '4xx.html', $data, $code );
                break;
            case 404:
                $data['title'] = 'Page Not Found';
                static::render( '4xx.html', $data, $code );
                break;
            case 405:
                $data['title'] = 'Method Not Allowed';
                static::render( '4xx.html', $data, $code );
                break;
            default:
                $code = 500;
                $data['title'] = 'Fatal Error';
                static::render( '5xx.html', $data, $code );
                break;
        }
        
    }


}