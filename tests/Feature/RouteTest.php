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
        Route::document("test/{id}", "test", \AMBERSIVE\Tests\Testfiles\Demo::class, [], false, \AMBERSIVE\Tests\Testfiles\Demo::class, []);

    }

    protected function tearDown(): void
    {
        parent::tearDown();
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

        Route::document("test/{id}", "test", \AMBERSIVE\Tests\Testfiles\Demo::class, ["auth"], false, \AMBERSIVE\Tests\Testfiles\Demo::class, []);

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
        Route::document("test/{id}", "test", \AMBERSIVE\Tests\Testfiles\Demo::class, ["auth"], false, \AMBERSIVE\Tests\Testfiles\Demo::class, []);

        $response = $this->get("/test/1");
        $response->assertStatus(302);

    }

    /**
     * Test if signed url param works and will fail if invalid url token
     */
    public function testIfRouteSignedWillWorkAndRequestWillFailWithForbidden(): void {

        Route::document("test/{id}", "test", \AMBERSIVE\Tests\Testfiles\Demo::class, [], true, \AMBERSIVE\Tests\Testfiles\Demo::class, []);

        $response = $this->get("/test/1");
        $response->assertStatus(403);

    }

    /**
     * Test if signed url params works and will return view if correct is passed.
     */
    public function testIfRouteSignedWillWorkAndRequestWillSucceedIfTokenIsPresent(): void {

        Route::document("test/{id}", "test", \AMBERSIVE\Tests\Testfiles\Demo::class, [], true, \AMBERSIVE\Tests\Testfiles\Demo::class, []);

        $url = URL::signedRoute('test', ['id' => 1]);

        $response = $this->get($url);
        $content = $response->getContent();

        if ($response->getStatusCode() !== 200){
            dd($content);
        }

        $response->assertStatus(200);
        $this->assertNotFalse(strpos($content, "Create your printable document here."));

    }
   

}