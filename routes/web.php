<?php

use Illuminate\Support\Facades\Route;
use App\Support\Tenant\CurrentOrganization;
use App\Http\Controllers\OrganizationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth',
    \App\Http\Middleware\ResolveOrganization::class,
])->get('/organization-test', function () {
    $organization = app(CurrentOrganization::class)->get();

    return response()->json([
        'id' => $organization->id,
        'uuid' => $organization->uuid,
        'name' => $organization->name,
        'slug' => $organization->slug,
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::post(
        '/organizations/{organization}/switch',
        [OrganizationController::class, 'switch']
    )->name('organizations.switch');
});