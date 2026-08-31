<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SaaSForge</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">

            <h1 class="text-2xl font-bold text-center mb-6">
                Sign in
            </h1>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block mb-1 font-medium">
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full border rounded px-3 py-2"
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1 font-medium">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full border rounded px-3 py-2"
                    >
                </div>

                <div class="flex items-center mb-6">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        value="1"
                        class="mr-2"
                    >

                    <label for="remember">
                        Remember me
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-black text-white rounded px-4 py-2"
                >
                    Sign in
                </button>
            </form>

        </div>
    </div>
</body>
</html>
