<?php

use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\ProjectController;
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

    // Educations
    Route::get('/educations', [EducationController::class, 'index']);
    Route::get('/educations/current', [EducationController::class, 'current']);
    Route::get('/educations/{id}', [EducationController::class, 'show']);

    // Certifications
    Route::get('/certifications', [CertificationController::class, 'index']);
    Route::get('/certifications/current', [CertificationController::class, 'current']);
    Route::get('/certifications/{id}', [CertificationController::class, 'show']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/current', [ProjectController::class, 'current']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
});
