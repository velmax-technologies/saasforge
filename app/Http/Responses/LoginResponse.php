<?php

namespace App\Http\Responses;

use App\Actions\Organization\AcceptOrganizationInvitation;
use App\Support\Tenant\CurrentOrganization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        protected AcceptOrganizationInvitation $acceptInvitation,
        protected CurrentOrganization $currentOrganization,
    ) {
    }

    public function toResponse($request)
    {
        $invitationToken = $request->session()->pull('invitation_token');

        /*
         * Normal login.
         */
        if (! $invitationToken) {
            return redirect()->intended(
                config('fortify.home', '/dashboard')
            );
        }

        $user = $request->user();

        /*
         * Login succeeded, so automatically accept
         * the invitation that brought the user here.
         */
        try {
            $invitation = $this->acceptInvitation->execute(
                $invitationToken,
                $user
            );
        } catch (ValidationException $exception) {
            /*
             * Return the user to the invitation page so they
             * can see why the invitation could not be accepted.
             */
            return redirect()
                ->route('invitations.show', $invitationToken)
                ->withErrors($exception->errors());
        }

        /*
         * Switch the active tenant to the organization
         * the user just joined.
         */
        $this->currentOrganization->set(
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
}