<?php

use App\Http\Controllers\Api\ExperienceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes for Next.js frontend
Route::prefix('v1')->group(function () {
    // Experiences
    Route::get('/experiences', [ExperienceController::class, 'index']);
    Route::get('/experiences/current', [ExperienceController::class, 'current']);
    Route::get('/experiences/{id}', [ExperienceController::class, 'show']);
});
