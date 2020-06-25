<?php

namespace AMBERSIVE\DocumentViewer\Macros;

use Route;
use Response;

use AMBERSIVE\DocumentViewer\Interfaces\DocumentInterface;

class ResponseDocument
{

    public function register(){

      Route::macro('document', function(String $route, String $name, $documentClass = null, array $middleware = [], bool $signed = false, $documentClassUpload = null, array $middlewareUpload = [])
      {

            // Add the signed middleware to the route
            if ($signed === true && in_array("", $middleware) === false) {
                $middleware[] = "signed";
            } 

            $documentClassInstance = new $documentClass();
            $documentClassUploadInstance = $documentClassUpload !== null ? new $documentClassUpload() : null;

            if ($documentClassInstance instanceof DocumentInterface === false) {
               abort(400, 'Invalid class type for Document');
            }

            Route::get($route, "\\${documentClass}@createDocument")->middleware($middleware)->name($name);

            if ($documentClassUploadInstance instanceof DocumentInterface === false) {
              return;
            }

            Route::post($route, "\\${documentClassUpload}@uploadDocument")->middleware($middlewareUpload)->name("${name}.upload");

            // Validation endpoint
            if ($documentClassInstance->useValidationEndpoint === false) {
              return;
            }

            Route::get("${route}/validation", "\\${documentClass}@validateDocument")->middleware($middleware)->name($name);

      });

    }

}
