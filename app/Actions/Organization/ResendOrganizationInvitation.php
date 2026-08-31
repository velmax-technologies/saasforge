<?php

namespace App\Actions\Organization;

use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResendOrganizationInvitation
{
    public function execute(
        OrganizationInvitation $invitation,
        User $user
    ): array {
        abort_unless(
            $user->hasPermission(
                'members.manage',
                $invitation->organization
            ),
            403
        );

        if ($invitation->status !== 'pending') {
            throw ValidationException::withMessages([
                'invitation' => 'Only pending invitations can be resent.',
            ]);
        }

        $email = Str::lower(trim($invitation->email));

        $alreadyMember = $invitation->organization
            ->users()
            ->whereRaw('LOWER(users.email) = ?', [$email])
            ->wherePivot('status', 'active')
            ->exists();

        if ($alreadyMember) {
            throw ValidationException::withMessages([
                'invitation' => 'This user is already an active member of the organization.',
            ]);
        }

        $rawToken = Str::random(64);

        $updated = DB::transaction(function () use (
            $invitation,
            $rawToken
        ) {
            $invitation->update([
                'token_hash' => hash('sha256', $rawToken),
                'expires_at' => now()->addDays(7),
            ]);

            return $invitation->fresh([
                'organization',
                'role',
            ]);
        });

        return [
            'invitation' => $updated,
            'token' => $rawToken,
            'url' => url('/invitations/' . $rawToken),
        ];
    }
}

