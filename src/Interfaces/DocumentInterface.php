<?php

namespace AMBERSIVE\DocumentViewer\Interfaces;

use Illuminate\Http\Request;

interface DocumentInterface {

    public function createDocument(Request $request);
    public function setData();
    public function returnView();

}