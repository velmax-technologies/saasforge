<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $invitationToken = $request->session()->pull(
            'invitation_token'
        );

        if ($invitationToken) {
            return redirect()->route(
                'invitations.show',
                ['token' => $invitationToken]
            );
        }

        return redirect()->intended(
            config('fortify.home', '/dashboard')
        );
    }
}