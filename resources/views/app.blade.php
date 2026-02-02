<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/ace-ada.ico') }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/App/Pages/{$page['component']}.vue"])
        @inertiaHead
        <script src="https://js.stripe.com/v3/"></script>
        @env('local')
            @vite('resources/js/aceada.js')
        @endenv
        @env(['dev', 'staging', 'uat', 'prod'])
            <script aceada-script src="{{ asset('dist/aceada.js') }}"></script>
        @endenv
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
