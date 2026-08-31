<?php

namespace App\Actions\Organization;

use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcceptOrganizationInvitation
{
    public function execute(
        string $rawToken,
        User $user
    ): OrganizationInvitation {
        $tokenHash = hash('sha256', $rawToken);

        return DB::transaction(function () use (
            $tokenHash,
            $user
        ) {
            $invitation = OrganizationInvitation::query()
                ->where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if (! $invitation) {
                throw ValidationException::withMessages([
                    'invitation' => 'This invitation is invalid or no longer available.',
                ]);
            }

            if ($invitation->status !== 'pending') {
                throw ValidationException::withMessages([
                    'invitation' => 'This invitation has already been used or revoked.',
                ]);
            }

            if ($invitation->isExpired()) {
                $invitation->update([
                    'status' => 'expired',
                ]);

                throw ValidationException::withMessages([
                    'invitation' => 'This invitation has expired.',
                ]);
            }

            /*
             * The invitation belongs to a specific email address.
             * Do not allow another account to claim it.
             */
            if (
                mb_strtolower(trim($user->email))
                !== mb_strtolower(trim($invitation->email))
            ) {
                throw ValidationException::withMessages([
                    'invitation' => 'This invitation was sent to a different email address.',
                ]);
            }

            $organization = $invitation->organization;

            if (! $organization || ! $organization->is_active) {
                throw ValidationException::withMessages([
                    'invitation' => 'This organization is no longer available.',
                ]);
            }

            /*
             * Check for an existing membership.
             */
            $membership = $organization->users()
                ->where('users.id', $user->id)
                ->first();

            if ($membership) {
                /*
                 * If the user already belongs to the organization,
                 * reactivate/update the membership instead of creating
                 * a duplicate row.
                 */
                $organization->users()->updateExistingPivot(
                    $user->id,
                    [
                        'role' => $invitation->role->slug,
                        'role_id' => $invitation->role_id,
                        'status' => 'active',
                        'joined_at' => now(),
                    ]
                );
            } else {
                $organization->users()->attach(
                    $user->id,
                    [
                        'role' => $invitation->role->slug,
                        'role_id' => $invitation->role_id,
                        'status' => 'active',
                        'joined_at' => now(),
                    ]
                );
            }

            $invitation->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            return $invitation->fresh([
                'organization',
                'role',
            ]);
        });
    }
}
