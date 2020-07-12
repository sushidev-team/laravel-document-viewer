<?php 

namespace AMBERSIVE\Tests;

use Config;
use File;

use AMBERSIVE\Tests\TestCase;

class DocumentAddTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
        shell_exec('rm -rf '.resource_path("views/printables"));
        shell_exec('rm -rf '.app_path("Printables"));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        shell_exec('rm -rf '.resource_path("views/printables"));
        shell_exec('rm -rf '.app_path("Printables"));
    }
    
    /**
     * Test if the command will needs a name paramter
     */
    public function testDocumentViewerAddCommandWillRequireNameParamter(): void {

        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $this->artisan('make:printable')->assertExitCode(1);

    }

    /**
     * Test if the command will work without any exception
     */
    public function testDocumentViewerAddCommandWillWork(): void {

        $this->artisan('make:printable test --force')->assertExitCode(0);

        $this->assertTrue(File::exists(base_path("app/Printables/test.php")));

        dd(shell_exec("ls ".resource_path("views/printables/test.blade.php")));

        $this->assertTrue(File::exists(resource_path("views/printables/test.blade.php")));
    }

    /**
     * Test if the command will not work if the document already exsits
     */
    public function testDocumentViewerAddFailIfDocumenteAlreadyExists():void {

        // Prepare
        $this->artisan('make:printable test')->assertExitCode(0);

        // Test
        $this->artisan('make:printable test')->expectsOutput('Printable already exists!')->assertExitCode(0);

    }

}