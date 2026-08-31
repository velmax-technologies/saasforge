<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use App\Models\OrganizationInvitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InviteOrganizationMember
{
    public function execute(
        Organization $organization,
        User $invitedBy,
        string $email,
        int $roleId
    ): array {
        $email = Str::lower(trim($email));

        abort_unless(
            $invitedBy->hasPermission(
                'members.manage',
                $organization
            ),
            403
        );

        $role = Role::query()->findOrFail($roleId);

        if (
            $role->slug === 'owner' &&
            ! $invitedBy->hasRole('owner', $organization)
        ) {
            abort(403);
        }

        $alreadyMember = $organization->users()
            ->whereRaw('LOWER(users.email) = ?', [$email])
            ->wherePivot('status', 'active')
            ->exists();

        if ($alreadyMember) {
            throw ValidationException::withMessages([
                'email' => 'This user is already an active member of the organization.',
            ]);
        }

        OrganizationInvitation::query()
            ->where('organization_id', $organization->id)
            ->whereRaw('LOWER(email) = ?', [$email])
            ->where('status', 'pending')
            ->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);

        $rawToken = Str::random(64);

        $invitation = DB::transaction(function () use (
            $organization,
            $invitedBy,
            $role,
            $email,
            $rawToken
        ) {
            return OrganizationInvitation::create([
                'organization_id' => $organization->id,
                'invited_by' => $invitedBy->id,
                'role_id' => $role->id,
                'email' => $email,
                'token_hash' => hash('sha256', $rawToken),
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);
        });

        return [
            'invitation' => $invitation,
            'token' => $rawToken,
            'url' => url('/invitations/' . $rawToken),
        ];
    }
}