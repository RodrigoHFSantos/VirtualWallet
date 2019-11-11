<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.Laravel = { csrfToken: '{{csrf_token()}}' }</script>

        <title>Virtual Wallet</title>
    
    </head>
    <body>
        <div id="app">
            <master></master>
        </div>  
        </div>

        <script src="js/app.js"></script> 
    </body>
</html>
