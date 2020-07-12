<?php

namespace AMBERSIVE\DocumentViewer\Console\Commands\Dev;

use Str;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class DocumentAdd extends GeneratorCommand
{
 
    protected $type = "Printable";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:printable {name} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a printable document.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $name = $this->qualifyClass($this->getNameInput());

        $path        = $this->getPath($name);
        $pathForView = $this->getPathForView($name);
        
        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);
        $this->makeDirectory($pathForView);

        $this->files->put($path, $this->sortImports($this->buildClassCustom($name, 'Printable')));
        $this->files->put($pathForView, $this->sortImports($this->buildClassCustom($name, 'PrintableView')));

        $this->info($this->type.' created successfully (data class and blade file).');

    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClassCustom(String $name, String $stubname)
    {
        $stub = $this->files->get($this->getStubFilePath($stubname));

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace():String
    {
        return $this->laravel->getNamespace()."Printables";
    }

    /**
     * Returns the path to the stubs folder
     */
    protected function getStub(): String {
        return __DIR__."/../../../Stubs/";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStubFilePath(String $stubname):String
    {
        return $this->getStub()."${stubname}.stub";
    }
    
    /**
     * Returns the path for the document class
     *
     * @param  mixed $name
     * @return String
     */
    protected function getPath($name):String {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->getPathFolder($name, config("document-viwer.path_class", 'app/Printables'));
    }
    
    /**
     * Returns the path for the view (blade) file
     *
     * @param  mixed $name
     * @return String
     */
    protected function getPathForView($name):String {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name).".blade";
        return str_replace("//", "/", strtolower(base_path(config("document-viewer.path_views", "resources/views/printables")."/".str_replace('\\', '/', $name).'.php')));
    }

        
    /**
     * Returns the base path for the file
     *
     * @param  mixed $name
     * @param  mixed $folder
     * @return String
     */
    protected function getPathFolder(String $name, String $folder = ''): String {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return base_path($folder.str_replace('\\', '/', $name).'.php');
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        
        $bladename = strtolower(str_replace("\\", ".", str_replace($this->rootNamespace()."\\", "printables.", $name)));

        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel', 'DummyBladeName'],
            [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel(), $bladename],
            $stub
        );

        return $this;
    }

}
