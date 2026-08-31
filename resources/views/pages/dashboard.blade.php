@extends('dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
            Welcome back, {{ auth()->user()->name }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Here's what's happening with your SaaSForge account.
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Organizations
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ auth()->user()->organizations()->count() }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Active Organizations
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ auth()->user()->organizations()->wherePivot('status', 'active')->count() }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Account
            </p>

            <p class="mt-2 text-lg font-semibold text-green-600">
                Active
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Role
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900">
                {{ auth()->user()->organizations->first()?->pivot->role ?? 'Member' }}
            </p>
        </div>

    </div>

    {{-- Organizations --}}
    <div class="mt-8 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="font-semibold text-gray-900">
                Your Organizations
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Organizations you have access to.
            </p>
        </div>

        <div class="divide-y divide-gray-100">

            @foreach(auth()->user()->organizations as $organization)

                <div class="flex items-center justify-between px-6 py-4">

                    <div>
                        <p class="font-medium text-gray-900">
                            {{ $organization->name }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $organization->email }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">

                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                            {{ ucfirst($organization->pivot->role) }}
                        </span>

                        @if($organization->pivot->status === 'active')
                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                Active
                            </span>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('organizations.switch', $organization) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
                            >
                                Switch
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endsection