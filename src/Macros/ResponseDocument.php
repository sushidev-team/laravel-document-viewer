<?php

namespace AMBERSIVE\DocumentViewer\Macros;

use Route;
use Response;

use AMBERSIVE\DocumentViewer\Interfaces\DocumentInterface;

class ResponseDocument
{

    public function register(){

      Route::macro('document', function(String $route, String $name, $documentClass = null, array $middleware = [], bool $signed = false, $postBack = null, array $postBackMiddleware = [])
      {

            // Add the signed middleware to the route
            if ($signed === true && in_array("", $middleware) === false) {
                $middleware[] = "signed";
            } 

            $documentClassInstance = new $documentClass();

            if ($documentClassInstance instanceof DocumentInterface === false) {
               abort(400, 'Invalid class type for Document');
            }

            Route::get($route, "\\${documentClass}@createDocument")->middleware($middleware)->name($name);

            if ($postBack !== null) {

               // Check if the controller is a
               Route::post("${route}", $postBack)->middleware($postBackMiddleware)->name("${name}.upload");

            }
        
      });

    }

}
