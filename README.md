# Document viewer

The goal of this project is the attemp to provide a helper functionality for creating beautiful, printable and secure documents.

!!! ATTENTION: This project is still work in progess !!! Do not use it until we release a stable candiate.

[![Maintainability](https://api.codeclimate.com/v1/badges/0f9d20da7317b5425e72/maintainability)](https://codeclimate.com/github/AMBERSIVE/laravel-document-viewer/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/0f9d20da7317b5425e72/test_coverage)](https://codeclimate.com/github/AMBERSIVE/laravel-document-viewer/test_coverage)

## Installation

```bash
composer require ambersive/documentviewer
```

## Usage

### Step 1: Create a printable

```bash
php artisan make:printable "Invoice"
```

### Step 2: Register the document viewer

Define a route in your desired router. Please be aware that default laravel middleware for web and api might require modifications there (eg for the post request).

```php

$signed = false

Route::document(
    "invoices/{id}", 
    "invoices", 
    \App\Printables\Invoice::class, 
    [
        // Middleware for getting the document
    ], 
    $signed, 
    \App\Printables\Invoice::class, // for file upload handling
    [
        // Middleware for postback / upload the document
    ]
);

```

The *signed* attribute will always be in effect for the get request of the document.

If you do not want a upload leave the upload class empty or null.

### Step 3: Modifiy the Class

The command above will create *Printable* Class in the folder *Printable* within app_path

First of all you should define data which is present or required in the specific blade file.

Then you should provide an uploadHandler. This will be required if you use our [Laravel Package for Print generation](https://github.com/AMBERSIVE/laravel-print-api) and the [Print-API](https://github.com/AMBERSIVE/print-api).

```php
use Illuminate\Http\Request;

use AMBERSIVE\DocumentViewer\Abstracts\DocumentAbstract;

class Invoice extends DocumentAbstract
{

    public array $data   = [];

    // Define the blade you want to return as printable document
    public String $blade = "ambersive.documentviewer::printable_default";
    public bool  $useValidationEndpoint = false; 

    public function setData(){
        // Request is available in $this->request
        // Save stuff in $this->data
        // This function must return $this (= chainable)
        // Params are provided in $this->params
        return $this;
    }

    public function uploadDocumentHandler(Request $request) {

        // Handle the file upload
        // Requires a response (preferable json)
        return ['status' => 200];

    }

    public function validateDocumentHandler(Request $request) {

        // Handle the validation
        // This information is a helper 
        // Requires a response (preferable json)
        return ['status' => 200];

    }

}
```

### Step 4: Modify the blade file

The make command also creates a blade file within the resource folder.
It will come with some basic scaffold settings so you will be able to create beautiful documents in no time.

But you can also define or resource the files.

```php
@extends('ambersive.documentviewer::printable')
```

A full example might look like:

```php

@extends('ambersive.documentviewer::printable')

@section('document')

    <div>Printable document</div>

@endsection

``` 

## Security Vulnerabilities

If you discover a security vulnerability within this package, please send an e-mail to Manuel Pirker-Ihl via [manuel.pirker-ihl@ambersive.com](mailto:manuel.pirker-ihl@ambersive.com). All security vulnerabilities will be promptly addressed.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).