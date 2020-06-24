<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Site specfic styles --> 
        @section('styles')
            <!-- This is the styles section -->
        @show

    </head>
    <body class="pageBody">

        <div class="pages">
    
            @section('document')
                <!-- Main section -->
            @show

        </div>

        <script>
            function printNow() {window.print();}
        </script>

    </body>
</html>