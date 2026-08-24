<?php

use App\Http\Controllers\FleetLiveTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/fleet');
});

Route::middleware(['web', 'auth', 'throttle:30,1'])
    ->get('/fleet/live-positions', [FleetLiveTrackingController::class, 'positions'])
    ->name('fleet.live-positions');
