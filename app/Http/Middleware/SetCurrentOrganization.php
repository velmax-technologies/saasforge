<?php

namespace App\Http\Middleware;

use App\Services\CurrentOrganization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentOrganization
{
    public function __construct(
        protected CurrentOrganization $currentOrganization
    ) {
    }

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if ($user) {
            $this->currentOrganization->setForUser($user);
        }

        return $next($request);
    }
}