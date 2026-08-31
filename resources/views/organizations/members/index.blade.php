@extends("dashboard")

@section("title", "Members")

@section("content")
<div class="mx-auto max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-900">
                Members
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage members of {{ $organization->name }}.
            </p>
        </div>

        @can("members.manage", $organization)
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Invite member
            </button>
        @endcan
    </div>

    {{-- Success --}}
    @if(session("success"))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session("success") }}
        </div>
    @endif

    {{-- Members --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Desktop table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Member
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Role
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Joined
                        </th>

                        @can("members.manage", $organization)
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Actions
                            </th>
                        @endcan
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">

                    @forelse($members as $member)

                        <tr class="hover:bg-gray-50">

                            {{-- Member --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                        {{ strtoupper(substr($member->name ?? $member->email, 0, 1)) }}
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $member->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $member->email }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $role = $member->pivot->role ?? "member";
                                @endphp

                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium capitalize text-gray-700">
                                    {{ $role }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @if($member->pivot->status === "active")

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Active
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium capitalize text-gray-600">
                                        {{ $member->pivot->status }}
                                    </span>

                                @endif

                            </td>

                            {{-- Joined --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ optional($member->pivot->joined_at)->format("M j, Y") ?? "—" }}
                            </td>

                            {{-- Actions --}}
                            @can("members.manage", $organization)
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <button
                                        type="button"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                    >
                                        Manage
                                    </button>
                                </td>
                            @endcan

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >
                                <div class="text-sm font-medium text-gray-900">
                                    No members found
                                </div>

                                <p class="mt-1 text-sm text-gray-500">
                                    Invite your first team member to get started.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="divide-y divide-gray-200 md:hidden">

            @forelse($members as $member)

                <div class="p-5">

                    <div class="flex items-center justify-between">

                        <div class="flex min-w-0 items-center">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                {{ strtoupper(substr($member->name ?? $member->email, 0, 1)) }}
                            </div>

                            <div class="ml-3 min-w-0">
                                <p class="truncate text-sm font-medium text-gray-900">
                                    {{ $member->name }}
                                </p>

                                <p class="truncate text-sm text-gray-500">
                                    {{ $member->email }}
                                </p>
                            </div>

                        </div>

                        <span class="ml-3 inline-flex shrink-0 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium capitalize text-gray-700">
                            {{ $member->pivot->role ?? "member" }}
                        </span>

                    </div>

                    <div class="mt-4 flex items-center justify-between text-sm">

                        @if($member->pivot->status === "active")
                            <span class="inline-flex items-center gap-1.5 text-green-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        @else
                            <span class="text-gray-500 capitalize">
                                {{ $member->pivot->status }}
                            </span>
                        @endif

                        <span class="text-gray-500">
                            {{ optional($member->pivot->joined_at)->format("M j, Y") ?? "—" }}
                        </span>

                    </div>

                </div>

            @empty

                <div class="px-6 py-12 text-center text-sm text-gray-500">
                    No members found.
                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if($members->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $members->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
