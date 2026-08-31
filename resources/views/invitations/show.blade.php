<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Organization Invitation — SaaSForge</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    <div class="flex min-h-screen items-center justify-center px-4 py-12">

        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="mb-8 text-center">
                <a
                    href="{{ url('/') }}"
                    class="text-2xl font-bold tracking-tight text-gray-900"
                >
                    SaaS<span class="text-indigo-600">Forge</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="p-6 sm:p-8">

                    {{-- Icon --}}
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50">
                        <svg
                            class="h-7 w-7 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"
                            />
                        </svg>
                    </div>

                    <div class="mt-5 text-center">

                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                            You're invited
                        </h1>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            You've been invited to join
                            <span class="font-semibold text-gray-900">
                                {{ $invitation->organization->name }}
                            </span>
                            on SaaSForge.
                        </p>

                    </div>

                    {{-- Invitation details --}}
                    <div class="mt-8 divide-y divide-gray-100 rounded-xl border border-gray-200">

                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">
                                Organization
                            </span>

                            <span class="max-w-[60%] truncate text-right text-sm font-medium text-gray-900">
                                {{ $invitation->organization->name }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">
                                Invited by
                            </span>

                            <span class="max-w-[60%] truncate text-right text-sm font-medium text-gray-900">
                                {{ $invitation->inviter->name ?? $invitation->inviter->email }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">
                                Email
                            </span>

                            <span class="max-w-[60%] truncate text-right text-sm font-medium text-gray-900">
                                {{ $invitation->email }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">
                                Role
                            </span>

                            <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold capitalize text-indigo-700">
                                {{ $invitation->role->name }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">
                                Expires
                            </span>

                            <span class="text-right text-sm font-medium text-gray-900">
                                {{ $invitation->expires_at->format('M j, Y') }}
                            </span>
                        </div>

                    </div>

                    {{-- Error --}}
                    @if($errors->has('invitation'))
                        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first('invitation') }}
                        </div>
                    @endif

                    {{-- Accept --}}
                    @auth

                        <form
                            method="POST"
                            action="{{ route('invitations.accept', $token) }}"
                            class="mt-6"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Accept invitation
                            </button>
                        </form>

                        <p class="mt-4 text-center text-xs leading-5 text-gray-500">
                            Make sure you're signed in with
                            <span class="font-medium text-gray-700">
                                {{ $invitation->email }}
                            </span>.
                        </p>

                    @else

                        <div class="mt-6 space-y-3">

                            <a
                                href="{{ route('login', ['invitation' => $token]) }}"
                                class="flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Sign in to accept
                            </a>

                            <p class="text-center text-xs leading-5 text-gray-500">
                                Don't have an account?
                                <a
                                    href="{{ route('register', ['invitation' => $token]) }}"
                                    class="font-medium text-indigo-600 hover:text-indigo-500"
                                >
                                    Create one
                                </a>
                            </p>

                        </div>

                    @endauth

                </div>

            </div>

            <p class="mt-6 text-center text-xs text-gray-400">
                This invitation expires
                {{ $invitation->expires_at->diffForHumans() }}.
            </p>

        </div>

    </div>

</body>
</html>
