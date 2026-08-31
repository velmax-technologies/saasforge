<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ResolveOrganization;
use App\Support\Tenant\CurrentOrganization;
use App\Http\Controllers\OrganizationController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth',
    ResolveOrganization::class,
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function (
    CurrentOrganization $currentOrganization
    ) {
        return view('pages.dashboard', [
            'organization' => $currentOrganization->get(),
            'organizations' => request()->user()->organizations()
                ->wherePivot('status', 'active')
                ->orderBy('name')
                ->get(),
        ]);
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Tenant Test
    |--------------------------------------------------------------------------
    */

    Route::get('/tenant-test', function (
        CurrentOrganization $currentOrganization
    ) {
        $organization = $currentOrganization->get();

        return response()->json([
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | Organization Switching
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/organizations/{organization}/switch',
        [OrganizationController::class, 'switch']
    )->name('organizations.switch');
});
