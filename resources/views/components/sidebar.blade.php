<aside class="hidden w-64 shrink-0 border-r border-gray-200 bg-white lg:block">

    <div class="flex h-16 items-center border-b border-gray-200 px-6">
        <a
            href="{{ route('dashboard') }}"
            class="text-xl font-bold tracking-tight text-gray-900"
        >
            SaaS<span class="text-indigo-600">Forge</span>
        </a>
    </div>

    <nav class="space-y-1 p-4">

        <a
            href="{{ route('dashboard') }}"
            class="flex items-center rounded-lg bg-indigo-50 px-3 py-2.5 text-sm font-medium text-indigo-700"
        >
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"
                />
            </svg>

            Dashboard
        </a>

        <a
            href="{{ route('organizations.settings', app(\App\Support\Tenant\CurrentOrganization::class)->get()) }}"
            class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
        >
            Organization Settings
        </a>

        <a
            href="{{ route('organizations.members.index', app(\App\Support\Tenant\CurrentOrganization::class)->get()) }}"
            class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-gray-900"
        >
            Members
        </a>

    </nav>

</aside>