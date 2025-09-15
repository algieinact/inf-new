<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResidenceApiController;
use App\Http\Controllers\Api\ActivityApiController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\BookmarkController;
use App\Http\Controllers\User\RatingController;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboardController;
use App\Http\Controllers\Provider\ResidenceController as ProviderResidenceController;
use App\Http\Controllers\Provider\ActivityController as ProviderActivityController;
use App\Http\Controllers\Provider\BookingManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;

Route::prefix('v1')->group(function () {

    // Public API endpoints
    Route::get('/residences', [ResidenceApiController::class, 'index']);
    Route::get('/residences/{residence}', [ResidenceApiController::class, 'show']);
    Route::get('/activities', [ActivityApiController::class, 'index']);
    Route::get('/activities/{activity}', [ActivityApiController::class, 'show']);

    // Authenticated API endpoints
    Route::middleware('auth:sanctum')->group(function () {

        // User endpoints
        Route::middleware('role:user')->group(function () {
            Route::post('/bookings', [UserBookingController::class, 'store']);
            Route::get('/bookings', [UserBookingController::class, 'index']);
            Route::patch('/bookings/{booking}', [UserBookingController::class, 'update']);

            Route::post('/bookmarks', [BookmarkController::class, 'store']);
            Route::get('/bookmarks', [BookmarkController::class, 'index']);
            Route::delete('/bookmarks', [BookmarkController::class, 'destroy']);

            Route::post('/ratings', [RatingController::class, 'store']);
            Route::delete('/ratings', [RatingController::class, 'destroy']);
        });

        // Provider endpoints
        Route::middleware('role:provider')->prefix('provider')->group(function () {
            Route::get('/dashboard', [ProviderDashboardController::class, 'index']);
            Route::apiResource('residences', ProviderResidenceController::class);
            Route::apiResource('activities', ProviderActivityController::class);
            Route::get('/bookings', [BookingManagementController::class, 'index']);
            Route::patch('/bookings/{booking}/approve', [BookingManagementController::class, 'approve']);
            Route::patch('/bookings/{booking}/reject', [BookingManagementController::class, 'reject']);
        });

        // Admin endpoints
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);
            Route::get('/analytics', [AdminDashboardController::class, 'analytics']);
            Route::apiResource('users', UserManagementController::class);
        });
    });
});
