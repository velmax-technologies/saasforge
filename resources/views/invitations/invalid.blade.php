<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Invitation Unavailable — SaaSForge</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    <div class="flex min-h-screen items-center justify-center px-4 py-12">

        <div class="w-full max-w-md text-center">

            {{-- Logo --}}
            <div class="mb-8">
                <a
                    href="{{ url('/') }}"
                    class="text-2xl font-bold tracking-tight text-gray-900"
                >
                    SaaS<span class="text-indigo-600">Forge</span>
                </a>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">

                {{-- Icon --}}
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50">
                    <svg
                        class="h-7 w-7 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18 18 6M6 6l12 12"
                        />
                    </svg>
                </div>

                <h1 class="mt-5 text-2xl font-bold tracking-tight text-gray-900">
                    Invitation unavailable
                </h1>

                <p class="mt-3 text-sm leading-6 text-gray-500">
                    {{ $message }}
                </p>

                <div class="mt-7">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Go to sign in
                    </a>
                </div>

            </div>

            <p class="mt-6 text-xs text-gray-400">
                If you believe this is a mistake, ask the organization administrator to send you a new invitation.
            </p>

        </div>

    </div>

</body>
</html>
