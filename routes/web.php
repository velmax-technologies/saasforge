<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ResolveOrganization;
use App\Support\Tenant\CurrentOrganization;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationMemberController;

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


    Route::get(
        '/organizations/{organization}/settings',
        [OrganizationController::class, 'settings']
    )->name('organizations.settings');

    Route::put(
        '/organizations/{organization}',
        [OrganizationController::class, 'update']
    )->name('organizations.update');

    Route::get(
        '/organizations/{organization}/members',
        [OrganizationMemberController::class, 'index']
    )->name('organizations.members.index');

    Route::post(
        '/organizations/{organization}/members/invite',
        [OrganizationMemberController::class, 'invite']
    )->name('organizations.members.invite');

    Route::patch(
        '/organizations/{organization}/members/{user}/role',
        [OrganizationMemberController::class, 'updateRole']
    )->name('organizations.members.role');

    Route::patch(
        '/organizations/{organization}/members/{user}/status',
        [OrganizationMemberController::class, 'updateStatus']
    )->name('organizations.members.status');


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
