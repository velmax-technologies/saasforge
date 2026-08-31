@extends("dashboard")

@section("title", "Members")

@section("content")

<div
    class="mx-auto max-w-6xl space-y-6"
    x-data="membersPage()"
    @keydown.escape.window="closeAllModals()"
>

```
{{-- =========================================================
    HEADER
========================================================== --}}
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
            @click="openInviteModal()"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <svg
                class="mr-2 h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Invite member
        </button>

    @endcan

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session("success"))

    <div
        class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
    >
        {{ session("success") }}
    </div>

@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">

        <p class="text-sm font-semibold text-red-800">
            Please correct the following errors:
        </p>

        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
    CREATED INVITATION URL
========================================================== --}}
@if(session("invitation_url"))

    <div
        class="rounded-xl border border-indigo-200 bg-indigo-50 p-4"
    >

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">

                <p class="text-sm font-semibold text-indigo-900">
                    Invitation created
                </p>

                <p class="mt-1 break-all text-sm text-indigo-700">
                    {{ session("invitation_url") }}
                </p>

            </div>

            <button
                type="button"
                @click="copyUrl(@js(session('invitation_url')), $event.currentTarget)"
                class="inline-flex shrink-0 items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500"
            >
                Copy invitation
            </button>

        </div>

    </div>

@endif


{{-- =========================================================
    PENDING INVITATIONS
========================================================== --}}
@if($invitations->isNotEmpty())

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-gray-200 px-6 py-4">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-base font-semibold text-gray-900">
                        Pending Invitations
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Invitations that have not yet been accepted.
                    </p>

                </div>

                <span
                    class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700"
                >
                    {{ $invitations->count() }}
                </span>

            </div>

        </div>


        {{-- =================================================
            DESKTOP INVITATIONS
        ================================================== --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Email
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Role
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Invited By
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Expires
                        </th>

                        @can("members.manage", $organization)

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Actions
                            </th>

                        @endcan

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200 bg-white">

                    @foreach($invitations as $invitation)

                        <tr class="hover:bg-gray-50">

                            {{-- Email --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="text-sm font-medium text-gray-900">
                                    {{ $invitation->email }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    Pending
                                </div>

                            </td>


                            {{-- Role --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <span
                                    class="inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium capitalize text-indigo-700"
                                >
                                    {{ $invitation->role->name ?? $invitation->role->slug }}
                                </span>

                            </td>


                            {{-- Invited By --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">

                                {{ $invitation->inviter->name ?? $invitation->inviter->email }}

                            </td>


                            {{-- Expires --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">

                                {{ $invitation->expires_at->format("M j, Y") }}

                            </td>


                            {{-- Actions --}}
                            @can("members.manage", $organization)

                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="inline-flex items-center gap-3">

                                        {{-- Copy --}}
                                        @if(session("invitation_url"))

                                            <button
                                                type="button"
                                                @click="copyUrl(@js(session('invitation_url')), $event.currentTarget)"
                                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                            >
                                                Copy
                                            </button>

                                        @endif


                                        {{-- Resend --}}
                                        <form
                                            method="POST"
                                            action="{{ route('organizations.members.invitation.resend', [$organization, $invitation]) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-sm font-medium text-gray-600 hover:text-gray-900"
                                            >
                                                Resend
                                            </button>

                                        </form>


                                        {{-- Revoke --}}
                                        <button
                                            type="button"
                                            @click="openRevokeModal(
                                                {{ $invitation->id }},
                                                @js($invitation->email)
                                            )"
                                            class="text-sm font-medium text-red-600 hover:text-red-500"
                                        >
                                            Revoke
                                        </button>

                                    </div>

                                </td>

                            @endcan

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =================================================
            MOBILE INVITATIONS
        ================================================== --}}
        <div class="divide-y divide-gray-200 md:hidden">

            @foreach($invitations as $invitation)

                <div class="space-y-4 p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-gray-900">
                                {{ $invitation->email }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $invitation->role->name ?? $invitation->role->slug }}
                            </p>

                        </div>

                        <span
                            class="inline-flex shrink-0 rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-700"
                        >
                            Pending
                        </span>

                    </div>


                    <div class="text-sm text-gray-500">

                        <div class="flex justify-between gap-4">

                            <span>
                                Invited by
                            </span>

                            <span class="truncate font-medium text-gray-700">
                                {{ $invitation->inviter->name ?? $invitation->inviter->email }}
                            </span>

                        </div>


                        <div class="mt-2 flex justify-between">

                            <span>
                                Expires
                            </span>

                            <span class="font-medium text-gray-700">
                                {{ $invitation->expires_at->format("M j, Y") }}
                            </span>

                        </div>

                    </div>


                    @can("members.manage", $organization)

                        <div class="flex items-center gap-4 border-t border-gray-100 pt-4">

                            @if(session("invitation_url"))

                                <button
                                    type="button"
                                    @click="copyUrl(@js(session('invitation_url')), $event.currentTarget)"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                >
                                    Copy
                                </button>

                            @endif


                            {{-- Resend --}}
                            <form
                                method="POST"
                                action="{{ route('organizations.members.invitation.resend', [$organization, $invitation]) }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="text-sm font-medium text-gray-600 hover:text-gray-900"
                                >
                                    Resend
                                </button>

                            </form>


                            {{-- Revoke --}}
                            <button
                                type="button"
                                @click="openRevokeModal(
                                    {{ $invitation->id }},
                                    @js($invitation->email)
                                )"
                                class="text-sm font-medium text-red-600 hover:text-red-500"
                            >
                                Revoke
                            </button>

                        </div>

                    @endcan

                </div>

            @endforeach

        </div>

    </div>

@endif


{{-- =========================================================
    MEMBERS
========================================================== --}}
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Desktop --}}
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

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700"
                                >
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

                            <span
                                class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium capitalize text-gray-700"
                            >
                                {{ $role }}
                            </span>

                        </td>


                        {{-- Status --}}
                        <td class="whitespace-nowrap px-6 py-4">

                            @if($member->pivot->status === "active")

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>

                            @else

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium capitalize text-gray-600"
                                >
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


    {{-- Mobile --}}
    <div class="divide-y divide-gray-200 md:hidden">

        @forelse($members as $member)

            <div class="p-5">

                <div class="flex items-center justify-between">

                    <div class="flex min-w-0 items-center">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700"
                        >
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

                    <span
                        class="ml-3 inline-flex shrink-0 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium capitalize text-gray-700"
                    >
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


{{-- =========================================================
    INVITE MODAL
========================================================== --}}
@can("members.manage", $organization)

    <div
        x-show="inviteModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="invite-modal-title"
        role="dialog"
        aria-modal="true"
    >

        {{-- Backdrop --}}
        <div
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
            @click="closeInviteModal()"
        ></div>


        {{-- Modal --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">

            <div
                x-show="inviteModal"
                x-transition
                @click.stop
                class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
            >

                {{-- Header --}}
                <div class="flex items-start justify-between border-b border-gray-200 px-6 py-5">

                    <div>

                        <h2
                            id="invite-modal-title"
                            class="text-lg font-semibold text-gray-900"
                        >
                            Invite member
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Invite someone to join {{ $organization->name }}.
                        </p>

                    </div>


                    <button
                        type="button"
                        @click="closeInviteModal()"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />

                        </svg>

                    </button>

                </div>


                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ route('organizations.members.invite', $organization) }}"
                    class="p-6"
                >

                    @csrf


                    {{-- Email --}}
                    <div>

                        <label
                            for="invite-email"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Email address
                        </label>

                        <input
                            id="invite-email"
                            name="email"
                            type="email"
                            value="{{ old("email") }}"
                            placeholder="member@example.com"
                            required
                            autofocus
                            class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error("email")

                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Role --}}
                    <div class="mt-5">

                        <label
                            for="invite-role"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Role
                        </label>

                        <select
                            id="invite-role"
                            name="role_id"
                            required
                            class="mt-2 block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            @foreach($roles as $role)

                                @if(
                                    $role->slug !== "owner" ||
                                    auth()->user()->hasRole("owner", $organization)
                                )

                                    <option
                                        value="{{ $role->id }}"
                                        @selected(old("role_id") == $role->id)
                                    >
                                        {{ $role->name }}
                                    </option>

                                @endif

                            @endforeach

                        </select>

                        @error("role_id")

                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Actions --}}
                    <div class="mt-7 flex justify-end gap-3">

                        <button
                            type="button"
                            @click="closeInviteModal()"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Send invitation
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =====================================================
        REVOKE CONFIRMATION MODAL
    ====================================================== --}}
    <div
        x-show="revokeModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-[60] overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="revoke-modal-title"
    >

        {{-- Backdrop --}}
        <div
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
            @click="closeRevokeModal()"
        ></div>


        {{-- Modal --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">

            <div
                x-show="revokeModal"
                x-transition
                @click.stop
                class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
            >

                {{-- Icon --}}
                <div class="px-6 pt-6">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">

                        <svg
                            class="h-6 w-6 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86l-7.18 12a2 2 0 001.71 3h14.36a2 2 0 001.71-3l-7.18-12a2 2 0 00-3.42 0z"
                            />

                        </svg>

                    </div>

                </div>


                {{-- Content --}}
                <div class="px-6 py-5">

                    <h2
                        id="revoke-modal-title"
                        class="text-lg font-semibold text-gray-900"
                    >
                        Revoke invitation?
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-500">

                        Are you sure you want to revoke the invitation sent to

                        <span
                            class="font-semibold text-gray-900"
                            x-text="revokeEmail"
                        ></span>?

                        The invitation link will no longer work.

                    </p>

                </div>


                {{-- Actions --}}
                <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                    <button
                        type="button"
                        @click="closeRevokeModal()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </button>


                    {{-- Revoke Form --}}
                    <form
                        method="POST"
                        :action="revokeAction"
                    >

                        @csrf

                        @method("DELETE")

                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        >
                            Revoke invitation
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endcan
```

</div>

{{-- =============================================================
ALPINE + PAGE JAVASCRIPT
============================================================= --}}

<script>

function membersPage() {

    return {

        inviteModal: false,

        revokeModal: false,

        revokeEmail: '',

        revokeAction: '',


        openInviteModal() {

            this.inviteModal = true;

            document.body.classList.add('overflow-hidden');

            this.$nextTick(() => {

                document.getElementById('invite-email')?.focus();

            });

        },


        closeInviteModal() {

            this.inviteModal = false;

            if (!this.revokeModal) {
                document.body.classList.remove('overflow-hidden');
            }

        },


        openRevokeModal(id, email) {

            this.revokeEmail = email;

            this.revokeAction =
                @js(url("/organizations/{$organization->id}/members")) +
                '/' +
                id;

            this.revokeModal = true;

            document.body.classList.add('overflow-hidden');

        },


        closeRevokeModal() {

            this.revokeModal = false;

            this.revokeEmail = '';

            this.revokeAction = '';

            if (!this.inviteModal) {
                document.body.classList.remove('overflow-hidden');
            }

        },


        closeAllModals() {

            this.inviteModal = false;

            this.revokeModal = false;

            this.revokeEmail = '';

            this.revokeAction = '';

            document.body.classList.remove('overflow-hidden');

        },


        async copyUrl(url, button) {

            if (!url || !button) {
                return;
            }

            const originalText = button.innerText;

            try {

                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {

                    await navigator.clipboard.writeText(url);

                } else {

                    const textarea = document.createElement('textarea');

                    textarea.value = url;

                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';

                    document.body.appendChild(textarea);

                    textarea.focus();
                    textarea.select();

                    document.execCommand('copy');

                    textarea.remove();

                }

                button.innerText = 'Copied!';

                setTimeout(() => {

                    button.innerText = originalText;

                }, 2000);

            } catch (error) {

                console.error(
                    'Failed to copy invitation URL:',
                    error
                );

            }

        }

    };

}

</script>

{{-- =============================================================
ALPINE CLOAK
============================================================= --}}

<style>
[x-cloak] {
    display: none !important;
}
</style>

@endsection
