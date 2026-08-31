<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Dashboard') - SaaSForge
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    <div class="min-h-screen">

        @include('components.sidebar')

        <div class="lg:pl-64">

            @include('components.topbar')

            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

</body>
</html>
