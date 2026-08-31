<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function switch(
        Request $request,
        Organization $organization
    ): RedirectResponse {
        $user = $request->user();

        abort_unless(
            $user->belongsToOrganization($organization),
            403
        );

        $request->session()->put(
            'current_organization_id',
            $organization->id
        );

        return back();
    }
}