<?php

namespace App\Actions\Organization;

use App\Models\OrganizationInvitation;
use App\Models\User;

class RevokeOrganizationInvitation
{
    public function execute(
        OrganizationInvitation $invitation,
        User $user
    ): OrganizationInvitation {
        abort_unless(
            $user->hasPermission(
                'members.manage',
                $invitation->organization
            ),
            403
        );

        if ($invitation->status !== 'pending') {
            return $invitation;
        }

        $invitation->update([
            'status' => 'revoked',
            'revoked_at' => now(),
        ]);

        return $invitation->fresh();
    }
}
