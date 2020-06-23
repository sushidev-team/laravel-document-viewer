<?php

namespace AMBERSIVE\DocumentViewer\Macros;

use Route;
use Response;

class ResponseDocument
{

    public function register(){

      Route::macro('document', function(String $name, String $route, $documentClass = null, array $middleware = [], bool $signed = false, $postBack = null, array $postBackMiddleware = [])
      {

            // Add the signed middleware to the route
            if ($signed === true && in_array("", $middleware) === false) {
                $middleware[] = "signed";
            } 

            Route::get("${route}", function () use ($documentClass) {
              dd($documentClass);
            })->middleware($middleware)->name($name);

            if ($postBack !== null) {

               // Check if the controller is a
               Route::post("${route}", $postBack)->middleware($postBackMiddleware)->name("${name}.upload");

            }
        
      });

    }

}
