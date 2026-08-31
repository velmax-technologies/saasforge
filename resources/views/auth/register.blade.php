<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account — SaaSForge</title>

    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    <div class="flex min-h-screen items-center justify-center px-4 py-12">

        <div class="w-full max-w-md">

            <div class="mb-8 text-center">
                <a
                    href="{{ url("/") }}"
                    class="text-2xl font-bold tracking-tight text-gray-900"
                >
                    SaaS<span class="text-indigo-600">Forge</span>
                </a>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="p-6 sm:p-8">

                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        Create your account
                    </h1>

                    <p class="mt-2 text-sm text-gray-500">
                        Create your SaaSForge account to continue.
                    </p>

                    @if($invitationToken)
                        <div class="mt-5 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                            You are joining an organization by invitation.
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route("register.store") }}"
                        class="mt-6 space-y-5"
                    >
                        @csrf

                        @if($invitationToken)
                            <input
                                type="hidden"
                                name="invitation"
                                value="{{ $invitationToken }}"
                            >
                        @endif

                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Name
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old("name") }}"
                                required
                                autofocus
                                autocomplete="name"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label
                                for="email"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Email
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old("email") }}"
                                required
                                autocomplete="email"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            @if($invitationToken)
                                <p class="mt-1.5 text-xs text-gray-500">
                                    Use the email address that received the invitation.
                                </p>
                            @endif
                        </div>

                        <div>
                            <label
                                for="password"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Password
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label
                                for="password_confirmation"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Confirm password
                            </label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Create account
                        </button>

                    </form>

                    <p class="mt-6 text-center text-sm text-gray-500">
                        Already have an account?
                        <a
                            href="{{ route("login", $invitationToken ? ["invitation" => $invitationToken] : []) }}"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            Sign in
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</body>
</html>