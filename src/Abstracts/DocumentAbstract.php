<?php

namespace AMBERSIVE\DocumentViewer\Abstracts;

use Illuminate\Http\Request;

use AMBERSIVE\DocumentViewer\Interfaces\DocumentInterface;

abstract class DocumentAbstract implements DocumentInterface
{            

    public $data = [];
    public $blade = "ambersive.documentviewer::printable_default";
    public Request $request;

    public function __contruct() {
        $this->request = request();
    }

    /**
     * Document will be called
     *
     * @param  mixed $request
     * @return void
     */
    public function createDocument(Request $request) {

        return  $this->setData()->returnView();

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
