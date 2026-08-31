<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard') — SaaSForge
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Main content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>