<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrganizationRequest;
use Illuminate\View\View;

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

    public function settings(
        Request $request,
        Organization $organization
    ): View {
        abort_unless(
            $request->user()->hasPermission(
                'organization.manage',
                $organization
            ),
            403
        );

        return view('organizations.settings', [
            'organization' => $organization,
        ]);
    }

    public function update(
        UpdateOrganizationRequest $request,
        Organization $organization
    ): RedirectResponse {
        $organization->update(
            $request->validated()
        );

        return back()->with(
            'success',
            'Organization settings updated successfully.'
        );
    }
}