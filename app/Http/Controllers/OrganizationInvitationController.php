<?php

namespace App\Http\Controllers;

use App\Actions\Organization\AcceptOrganizationInvitation;
use App\Models\OrganizationInvitation;
use App\Support\Tenant\CurrentOrganization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrganizationInvitationController extends Controller
{
    public function show(
        Request $request,
        string $token
    ): View|RedirectResponse {
        $invitation = $this->findInvitation($token);

        if (! $invitation) {
            return view('invitations.invalid', [
                'message' => 'This invitation is invalid or no longer available.',
            ]);
        }

        if ($invitation->status !== 'pending') {
            return view('invitations.invalid', [
                'message' => 'This invitation has already been used or revoked.',
            ]);
        }

        if ($invitation->isExpired()) {
            $invitation->update([
                'status' => 'expired',
            ]);

            return view('invitations.invalid', [
                'message' => 'This invitation has expired.',
            ]);
        }

        return view('invitations.show', [
            'invitation' => $invitation,
            'token' => $token,
        ]);
    }

    public function accept(
        Request $request,
        string $token,
        AcceptOrganizationInvitation $acceptInvitation,
        CurrentOrganization $currentOrganization
    ): RedirectResponse {
        $request->validate([
            'invitation' => ['nullable'],
        ]);

        $user = $request->user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('invitation_token', $token);
        }

        try {
            $invitation = $acceptInvitation->execute(
                $token,
                $user
            );
        } catch (ValidationException $exception) {
            throw $exception;
        }

        $currentOrganization->set(
            $invitation->organization
        );

        $request->session()->put(
            'current_organization_id',
            $invitation->organization_id
        );

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'You have successfully joined ' .
                $invitation->organization->name .
                '.'
            );
    }

    protected function findInvitation(
        string $token
    ): ?OrganizationInvitation {
        return OrganizationInvitation::query()
            ->with([
                'organization',
                'role',
                'inviter',
            ])
            ->where(
                'token_hash',
                hash('sha256', $token)
            )
            ->first();
    }
}
