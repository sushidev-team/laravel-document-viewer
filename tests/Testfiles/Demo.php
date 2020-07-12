<?php

namespace AMBERSIVE\Tests\Testfiles;

use Illuminate\Http\Request;

use AMBERSIVE\DocumentViewer\Abstracts\DocumentAbstract;

class Demo extends DocumentAbstract
{

    public array $data   = [];
    public String $blade = "ambersive.testing::demo";
    public bool  $useValidationEndpoint = false; 

    /**
     * Set the data of this printable
     */
    public function setData(){
        // Request is available in $this->request
        // Save stuff in $this->data
        return $this;
    }

    public function uploadDocumentHandler(Request $request) {

        // Handle the file upload
        // Requires a response (preferable json)

    }

    public function validateDocumentHandler(Request $request) {

        // Handle the validation
        return ['status' => 200]; 

    }

}
