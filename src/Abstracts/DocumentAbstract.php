<?php

namespace AMBERSIVE\DocumentViewer\Abstracts;

use Illuminate\Http\Request;

use AMBERSIVE\DocumentViewer\Interfaces\DocumentInterface;

abstract class DocumentAbstract implements DocumentInterface
{            

    public array $data   = [];
    public array $params = [];
    public bool  $useValidationEndpoint = false;

    public String $blade = "ambersive.documentviewer::printable_default";

    public Request $request;

    public function __construct() {
        $this->request = request();
    }

    /**
     * Document will be called and created
     *
     * @param  mixed $request
     * @return void
     */
    public function createDocument(Request $request) {
        $this->params = $request->route()->parameters;
        return  $this->setData()->returnView();

    }
    
    /**
     * Handle a post of a file
     * @param  mixed $request
     */
    public function uploadDocument(Request $request) {
        $this->params = $request->route()->parameters;
        if (method_exists($this, 'uploadDocumentHandler') === false) {
            abort(400, 'Document cannot be uploaded.');
        }
        $result = $this->uploadDocumentHandler($request);
        if ($result === null) {
            abort(400, 'Error while uploading the file.');
        }
        return $result;
    }
        
    /**
     * Handle the validation request for the document
     * Should return a json object if document requirement exists.
     *
     * @param  mixed $request
     * @return void
     */
    public function validateDocument(Request $request) {
        $this->params = $request->route()->parameters;
        if (method_exists($this, 'validateDocumentHandler') === false) {
            abort(400, 'Document cannot be validated.');
        }
        $result = $this->validateDocumentHandler($request);
        if ($result === null) {
            abort(400, 'Error while validating the file.');
        }
        return $result;
    }

    /**
     * Set the data of this printable
     */
    public function setData(){
        // Request is available in $this->request
        // Save stuff in $this->data
        return $this;
    }
    
    /**
     * Return View is responsable for the handling the blade response
     * ambersive.documentviewer::printable_default (= Default document for )
     */
    public function returnView() {
        return view($this->blade, $this->data);
    }
}
