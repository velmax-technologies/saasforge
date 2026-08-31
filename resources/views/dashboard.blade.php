@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">

        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
            Welcome back, {{ auth()->user()->name }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Here's what's happening with your SaaSForge account.
        </p>

    </div>

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
                Members
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ auth()->user()->organizations()->withCount('users')->get()->sum('users_count') }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Current Organization
            </p>

            <p class="mt-2 truncate text-lg font-bold text-gray-900">
                {{ app(\App\Support\Tenant\CurrentOrganization::class)->get()->name }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Account
            </p>

            <p class="mt-2 text-lg font-bold text-green-600">
                Active
            </p>
        </div>

    </div>

@endsection