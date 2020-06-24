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
    protected $signature = 'make:printable {name}';

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

        $path = $this->getPath(ucfirst($name));
        
        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type.' created successfully.');

    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace()."Printables";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub():String
    {
        return __DIR__.'/../../../Stubs/Printable.stub';
    }
    
    /**
     * Returns the path for the document
     *
     * @param  mixed $name
     * @return String
     */
    protected function getPath($name):String
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'].'/Printables/'.str_replace('\\', '/', $name).'.php';
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

        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel()],
            $stub
        );

        return $this;
    }

}
