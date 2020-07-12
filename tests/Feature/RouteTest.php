<?php 

namespace AMBERSIVE\Tests\Feature;

use Artisan;
use Config;
use File;
use Route;

use Illuminate\Support\Facades\URL;

use AMBERSIVE\Tests\TestCase;

class RouteTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
        shell_exec('rm -rf '.resource_path("views/printables"));
        shell_exec('rm -rf '.app_path("Printables"));

        $this->artisan('make:printable test --force')->assertExitCode(0);

        Route::document("test/{id}", "test", \App\Printables\test::class, [], false, \App\Printables\test::class, []);        

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        shell_exec('rm -rf '.resource_path("views/printables"));
        shell_exec('rm -rf '.app_path("Printables"));
    }

    /**
     * Test if the document 
     */
    public function testIfRouteWillBeRegisteredAndWillReturnTheDocument():void {

        $response = $this->get("test/1");
        $content = $response->getContent();

        $response->assertStatus(200);
        $this->assertNotFalse(strpos($content, "Create your printable document here."));

    }
    
    /**
     * Test it a reference to auth will end in server 500 error if no login route defined
     */
    public function testIfRouteWillFailWithServerError():void {

        Route::document("test/{id}", "test", \App\Printables\test::class, ["auth"], false, \App\Printables\test::class, []);

        $response = $this->get("/test/1");
        $content = $response->getContent();

        $response->assertStatus(500);
        $this->assertNotFalse(strpos($content, "Route [login] not defined"));

    }

    /**
     * Test if route middleware can be attached
     */
    public function testIfRouteWillFailWillFailWithAnRedirect():void {

        Route::get("login", function(){})->name("login");
        Route::document("test/{id}", "test", \App\Printables\test::class, ["auth"], false, \App\Printables\test::class, []);

        $response = $this->get("/test/1");
        $response->assertStatus(302);

    }

    /**
     * Test if signed url param works and will fail if invalid url token
     */
    public function testIfRouteSignedWillWorkAndRequestWillFailWithForbidden(): void {

        Route::document("test/{id}", "test", \App\Printables\test::class, [], true, \App\Printables\test::class, []);

        $response = $this->get("/test/1");
        $response->assertStatus(403);

    }

    /**
     * Test if signed url params works and will return view if correct is passed.
     */
    public function testIfRouteSignedWillWorkAndRequestWillSucceedIfTokenIsPresent(): void {

        Route::document("test/{id}", "test", \App\Printables\test::class, [], true, \App\Printables\test::class, []);

        $url = URL::signedRoute('test', ['id' => 1]);

        $response = $this->get($url);
        $content = $response->getContent();

        $response->assertStatus(200);
        $this->assertNotFalse(strpos($content, "Create your printable document here."));

    }
   

}