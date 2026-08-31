<?php

use Illuminate\Support\Facades\Route;
use App\Services\CurrentOrganization;
use App\Http\Controllers\OrganizationController;
use App\Http\Middleware\SetCurrentOrganization;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/debug-auth', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'session_id' => $request->session()->getId(),
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'user' => auth()->user()?->only(['id', 'email']),
        'session' => $request->session()->all(),
        'cookies' => $request->cookies->all(),
    ]);
});

Route::middleware([
    'auth',
    SetCurrentOrganization::class,
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/tenant-test', function (CurrentOrganization $currentOrganization) {
        $organization = $currentOrganization->get();

        return response()->json([
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
        ]);
    });

    Route::post(
        '/organizations/{organization}/switch',
        [OrganizationController::class, 'switch']
    )->name('organizations.switch');
});