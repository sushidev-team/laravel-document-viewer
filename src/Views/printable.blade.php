<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        
        <title>@yield('title')</title>
        
        @include('ambersive.documentviewer::printable_scripts')

        <!-- Page preview styles --> 
        <style>
            /* CSS for Paged.js interface */
    
            :root {
                --color-background: #fafafa;
                --color-pageBox: #999;
                --color-paper: #fff;
                --color-marginBox: transparent;
                --color-button: #3fda59;
            }

            /* To define how the book look on the screen: */
            @media screen {
                body {
                    background-color: var(--color-background);
                }

                .pagedjs_pages {
                    display: flex;
                    flex: 0;
                    flex-wrap: wrap;
                    flex-direction: column;
                    width: 100%;
                    margin: 0 auto;
                }

                .pagedjs_page {
                    background-color: var(--color-paper);
                    box-shadow: 0 0 0 1px var(--color-pageBox);
                    flex-shrink: 0;
                    flex-grow: 0;
                    margin: 0 auto;
                    margin-top: 5mm;
                }

                .pagedjs_page:last-of-type {
                    margin-bottom: 1mm;
                }

                /* show the margin-box */

                .pagedjs_margin-top-left-corner-holder,
                .pagedjs_margin-top,
                .pagedjs_margin-top-left,
                .pagedjs_margin-top-center,
                .pagedjs_margin-top-right,
                .pagedjs_margin-top-right-corner-holder,
                .pagedjs_margin-bottom-left-corner-holder,
                .pagedjs_margin-bottom,
                .pagedjs_margin-bottom-left,
                .pagedjs_margin-bottom-center,
                .pagedjs_margin-bottom-right,
                .pagedjs_margin-bottom-right-corner-holder,
                .pagedjs_margin-right,
                .pagedjs_margin-right-top,
                .pagedjs_margin-right-middle,
                .pagedjs_margin-right-bottom,
                .pagedjs_margin-left,
                .pagedjs_margin-left-top,
                .pagedjs_margin-left-middle,
                .pagedjs_margin-left-bottom {
                    box-shadow: 0 0 0 1px inset var(--color-marginBox);
                }
            }

        </style>

        @section('styles')
        <!-- Custom styles -->
        @show

    </head>
    <body>

        <div class="pages">
    
            @section('document')
                <!-- Main section -->
            @show

        </div>

    </body>
</html>