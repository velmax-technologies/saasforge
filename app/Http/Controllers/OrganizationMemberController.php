<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMemberRoleRequest;
use App\Http\Requests\UpdateMemberStatusRequest;
use App\Actions\Organization\InviteOrganizationMember;
use App\Http\Requests\InviteOrganizationMemberRequest;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationMemberController extends Controller
{
    public function index(
        Request $request,
        Organization $organization
    ): View {
        $this->authorizeOrganizationAccess(
            $request,
            $organization,
            'members.view'
        );

        $members = $organization->users()
            ->withPivot([
                'role',
                'role_id',
                'status',
                'joined_at',
            ])
            ->orderBy('name')
            ->paginate(15);

        $roles = Role::query()
            ->orderBy('id')
            ->get();

        return view('organizations.members.index', [
            'organization' => $organization,
            'members' => $members,
            'roles' => $roles,
        ]);
    }

    public function updateRole(
        UpdateMemberRoleRequest $request,
        Organization $organization,
        User $user
    ): RedirectResponse {
        $this->ensureMember(
            $organization,
            $user
        );

        $membership = $organization->users()
            ->where('users.id', $user->id)
            ->first()
            ?->pivot;

        abort_unless($membership, 404);

        $newRole = Role::findOrFail(
            $request->integer('role_id')
        );

        /*
         * Only an owner can assign the owner role.
         */
        if (
            $newRole->slug === 'owner' &&
            ! $request->user()->hasRole(
                'owner',
                $organization
            )
        ) {
            abort(403);
        }

        /*
         * Protect the last owner.
         */
        if (
            $membership->role === 'owner' &&
            $newRole->slug !== 'owner'
        ) {
            $ownerCount = $organization->users()
                ->wherePivot('role', 'owner')
                ->wherePivot('status', 'active')
                ->count();

            abort_if(
                $ownerCount <= 1,
                422,
                'The organization must have at least one owner.'
            );
        }

        $organization->users()->updateExistingPivot(
            $user->id,
            [
                'role' => $newRole->slug,
                'role_id' => $newRole->id,
            ]
        );

        return back()->with(
            'success',
            'Member role updated successfully.'
        );
    }

    public function updateStatus(
        UpdateMemberStatusRequest $request,
        Organization $organization,
        User $user
    ): RedirectResponse {
        $this->ensureMember(
            $organization,
            $user
        );

        $membership = $organization->users()
            ->where('users.id', $user->id)
            ->first()
            ?->pivot;

        abort_unless($membership, 404);

        /*
         * Do not allow the last active owner
         * to be suspended.
         */
        if (
            $membership->role === 'owner' &&
            $request->input('status') !== 'active'
        ) {
            $activeOwners = $organization->users()
                ->wherePivot('role', 'owner')
                ->wherePivot('status', 'active')
                ->count();

            abort_if(
                $activeOwners <= 1,
                422,
                'The last active owner cannot be suspended.'
            );
        }

        $organization->users()->updateExistingPivot(
            $user->id,
            [
                'status' => $request->input('status'),
            ]
        );

        return back()->with(
            'success',
            'Member status updated successfully.'
        );
    }

    protected function ensureMember(
        Organization $organization,
        User $user
    ): void {
        abort_unless(
            $organization->users()
                ->where('users.id', $user->id)
                ->exists(),
            404
        );
    }

    protected function authorizeOrganizationAccess(
        Request $request,
        Organization $organization,
        string $permission
    ): void {
        abort_unless(
            $request->user()->hasPermission(
                $permission,
                $organization
            ),
            403
        );
    }

    public function invite(
        InviteOrganizationMemberRequest $request,
        Organization $organization,
        InviteOrganizationMember $inviteOrganizationMember
    ): RedirectResponse {
        $result = $inviteOrganizationMember->execute(
            $organization,
            $request->user(),
            $request->validated('email'),
            $request->validated('role_id')
        );

        return back()->with([
            'success' => 'Invitation created successfully.',
            'invitation_url' => $result['url'],
        ]);
    }
}