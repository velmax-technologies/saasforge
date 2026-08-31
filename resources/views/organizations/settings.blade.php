@extends("dashboard")

@section("title", "Organization Settings")

@section("content")
<div class="mx-auto max-w-5xl space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-semibold tracking-tight text-gray-900">
            Organization Settings
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Manage your organization profile and regional settings.
        </p>
    </div>

    {{-- Success message --}}
    @if(session("success"))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session("success") }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route("organizations.update", $organization) }}"
        class="space-y-6"
    >
        @csrf
        @method("PUT")

        {{-- General --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">
                <h3 class="text-base font-semibold text-gray-900">
                    General
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Basic information about your organization.
                </p>
            </div>

            <div class="space-y-5 p-6">

                {{-- Name --}}
                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Organization name
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old("name", $organization->name) }}"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Slug --}}
                <div>
                    <label
                        for="slug"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Slug
                    </label>

                    <div class="mt-2 flex rounded-lg shadow-sm">
                        <span class="inline-flex items-center rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                            /
                        </span>

                        <input
                            id="slug"
                            name="slug"
                            type="text"
                            value="{{ old("slug", $organization->slug) }}"
                            required
                            class="block w-full rounded-r-lg border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                </div>

                {{-- Email --}}
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
                        value="{{ old("email", $organization->email) }}"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Phone --}}
                <div>
                    <label
                        for="phone"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Phone
                    </label>

                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old("phone", $organization->phone) }}"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

            </div>
        </div>

        {{-- Regional --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">
                <h3 class="text-base font-semibold text-gray-900">
                    Regional Settings
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Configure timezone and language preferences.
                </p>
            </div>

            <div class="grid gap-5 p-6 md:grid-cols-2">

                {{-- Timezone --}}
                <div>
                    <label
                        for="timezone"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Timezone
                    </label>

                    <select
                        id="timezone"
                        name="timezone"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach(timezone_identifiers_list() as $timezone)
                            <option
                                value="{{ $timezone }}"
                                @selected(old("timezone", $organization->timezone) === $timezone)
                            >
                                {{ $timezone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Locale --}}
                <div>
                    <label
                        for="locale"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Locale
                    </label>

                    <select
                        id="locale"
                        name="locale"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="en" @selected(old("locale", $organization->locale) === "en")>
                            English
                        </option>

                        <option value="sw" @selected(old("locale", $organization->locale) === "sw")>
                            Swahili
                        </option>
                    </select>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">

            <a
                href="{{ route("dashboard") }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Save changes
            </button>

        </div>

    </form>

</div>
@endsection
