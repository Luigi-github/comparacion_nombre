<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Buscador de personas</title>
    </head>
    <body>
        <div id="app">
            @yield("content")
        </div>
        <script src="{{ asset('js/app.js ') }}" defer></script>
    </body>
</html>
