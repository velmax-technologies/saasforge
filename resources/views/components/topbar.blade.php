<header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">

    <div class="flex items-center gap-3">

        {{-- Mobile logo --}}
        <a
            href="{{ route('dashboard') }}"
            class="text-lg font-bold tracking-tight text-gray-900 lg:hidden"
        >
            SaaS<span class="text-indigo-600">Forge</span>
        </a>

        <h1 class="hidden text-lg font-semibold text-gray-900 sm:block">
            @yield('title', 'Dashboard')
        </h1>

    </div>

    <div class="flex items-center gap-4">

       @php
    $currentOrganization = app(
        \App\Support\Tenant\CurrentOrganization::class
    );

    $organization = $currentOrganization->check()
        ? $currentOrganization->get()
        : null;
@endphp

@if($organization)
    <div class="relative">

        <details class="group">
            <summary
                class="flex cursor-pointer list-none items-center gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2 hover:bg-gray-50"
            >
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-sm font-semibold text-indigo-700">
                    {{ strtoupper(substr($organization->name, 0, 1)) }}
                </div>

                <div class="hidden text-left sm:block">
                    <p class="max-w-40 truncate text-sm font-medium text-gray-900">
                        {{ $organization->name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        Organization
                    </p>
                </div>

                <svg
                    class="h-4 w-4 text-gray-400 transition group-open:rotate-180"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m6 9 6 6 6-6"
                    />
                </svg>
            </summary>

            <div class="absolute right-0 z-50 mt-2 w-72 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">

                <div class="border-b border-gray-100 px-4 py-3">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Switch organization
                    </p>
                </div>

                <div class="max-h-80 overflow-y-auto p-2">

                    @foreach(auth()->user()->organizations()->wherePivot('status', 'active')->get() as $userOrganization)

                        <form
                            method="POST"
                            action="{{ route('organizations.switch', $userOrganization) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left hover:bg-gray-50"
                            >

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm font-semibold text-gray-600">
                                    {{ strtoupper(substr($userOrganization->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $userOrganization->name }}
                                    </p>

                                    <p class="truncate text-xs text-gray-500">
                                        {{ ucfirst($userOrganization->pivot->role) }}
                                    </p>
                                </div>

                                @if($userOrganization->id === $organization->id)
                                    <svg
                                        class="h-5 w-5 text-indigo-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m5 13 4 4L19 7"
                                        />
                                    </svg>
                                @endif

                            </button>
                        </form>

                    @endforeach

                </div>

                <div class="border-t border-gray-100 p-2">

                    <a
                        href="#"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Manage organizations
                    </a>

                </div>

            </div>
        </details>

    </div>
@endif

        @auth

            <div class="hidden text-right sm:block">
                <p class="text-sm font-medium text-gray-900">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-xs text-gray-500">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Logout
                </button>
            </form>

        @endauth

    </div>

</header>