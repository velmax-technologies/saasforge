<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Support\Tenant\CurrentOrganization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveOrganization
{
    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $organizationId = $request->session()->get(
            'current_organization_id'
        );

        $organization = null;

        if ($organizationId) {
            $organization = $user->organizations()
                ->where('organizations.id', $organizationId)
                ->wherePivot('status', 'active')
                ->first();
        }

        if (! $organization) {
            $organization = $user->organizations()
                ->wherePivot('status', 'active')
                ->orderBy('organizations.id')
                ->first();

            if ($organization) {
                $request->session()->put(
                    'current_organization_id',
                    $organization->id
                );
            }
        }

        if ($organization) {
            app(CurrentOrganization::class)->set($organization);
        }

        return $next($request);
    }
}