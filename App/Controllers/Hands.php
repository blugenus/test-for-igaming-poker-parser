<?php
namespace App\Controllers;

use App\Models\Games as Games;

/**
 * Hands controller - handles the file upload and outputing results
 */
class Hands extends \App\Controller {

    /**
     * Sends the upload page to the browser
     * 
     * @return void
     */
    public function showUploadPage(){
        static::render( 'upload.html' );
    }
    
    /**
     * Processing the uploaded hands file and send the result page to the browser
     * 
     * @return void
     */
    public function processHandsFile(){
        // since we are not going to keep the file, we are gonna use the tmp file directly.
        $data = Games::processFile( $_FILES["fileToUpload"]["tmp_name"] ); 
        static::render( 'result.html', $data );
    }
    
}