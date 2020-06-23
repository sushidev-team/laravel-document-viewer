<?php

namespace AMBERSIVE\DocumentViewer;

use Illuminate\Support\ServiceProvider;

class DocumentViewerServiceProvider extends ServiceProvider
{

    public $macros = [
        \AMBERSIVE\DocumentViewer\Macros\ResponseDocument::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        // Macros

        collect($this->macros)->each(function($macro){

            $cl = new $macro();
            if(method_exists($cl, 'register') === true){
                $cl->register();
            }

        });

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
               
            ]);
        }

        // Configs
        $this->publishes([
            __DIR__.'/Configs/document-viewer.php'         => config_path('document-viewer.php'),
        ],'document-viewer');

        $this->mergeConfigFrom(
            __DIR__.'/Configs/document-viewer.php', 'document-viewer.php'
        );

    }

}
