<?php

namespace AMBERSIVE\DocumentViewer;

use Illuminate\Support\ServiceProvider;

class DocumentViewerServiceProvider extends ServiceProvider
{

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
