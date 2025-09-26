<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResidenceApiController;
use App\Http\Controllers\Api\ActivityApiController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\{
    ProfileController as UserProfileController,
    ResidenceController as UserResidenceController,
    ActivityController as UserActivityController,
    BookingController as UserBookingController,
    BookmarkController as UserBookmarkController,
    RatingController as UserRatingController
};
use App\Http\Controllers\Api\Provider\{
    DashboardController as ProviderDashboardController,
    ResidenceController as ProviderResidenceController,
    ActivityController as ProviderActivityController,
    BookingManagementController as ProviderBookingManagementController
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    UserManagementController
};

Route::prefix('v1')->group(function () {

    /** -------------------------
     * Public API
     * ------------------------- */
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/search', [HomeController::class, 'search']);
    Route::get('/categories', [HomeController::class, 'categories']);
    Route::get('/residences', [ResidenceApiController::class, 'index']);
    Route::get('/residences/{residence}', [ResidenceApiController::class, 'show']);
    Route::get('/activities', [ActivityApiController::class, 'index']);
    Route::get('/activities/{activity}', [ActivityApiController::class, 'show']);

    /** -------------------------
     * Authentication
     * ------------------------- */
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    /** -------------------------
     * Protected Routes
     * ------------------------- */
    Route::middleware('auth:sanctum')->group(function () {

        /** --- User --- */
        Route::middleware('role:user')->prefix('user')->group(function () {
            Route::get('/profile', [UserProfileController::class, 'show']);
            Route::put('/profile', [UserProfileController::class, 'update']);

            Route::apiResource('residences', UserResidenceController::class)->only(['index', 'show']);
            Route::apiResource('activities', UserActivityController::class)->only(['index', 'show']);
            Route::apiResource('bookings', UserBookingController::class)->except(['create', 'edit']);
            Route::patch('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel']);
            Route::match(['get','post'], '/bookings/{booking}/payment', [UserBookingController::class, 'payment']);

            Route::apiResource('bookmarks', UserBookmarkController::class)->only(['index', 'store', 'destroy']);
            Route::post('/bookmarks/toggle', [UserBookmarkController::class, 'toggle']);

            Route::apiResource('ratings', UserRatingController::class)->except(['create', 'edit']);
        });

        /** --- Provider --- */
        Route::middleware('role:provider')->prefix('provider')->group(function () {
            Route::get('/dashboard', [ProviderDashboardController::class, 'index']);
            Route::get('/dashboard/charts', [ProviderDashboardController::class, 'getChartData']);
            Route::get('/dashboard/stats', [ProviderDashboardController::class, 'getStats']);
            Route::get('/dashboard/export', [ProviderDashboardController::class, 'exportData']);

            Route::apiResource('residences', ProviderResidenceController::class);
            Route::patch('/residences/{residence}/toggle-status', [ProviderResidenceController::class, 'toggleStatus']);

            Route::apiResource('activities', ProviderActivityController::class);
            Route::patch('/activities/{activity}/toggle-status', [ProviderActivityController::class, 'toggleStatus']);

            Route::get('/bookings', [ProviderBookingManagementController::class, 'index']);
            Route::get('/bookings/{booking}', [ProviderBookingManagementController::class, 'show']);
            Route::patch('/bookings/{booking}/approve', [ProviderBookingManagementController::class, 'approve']);
            Route::patch('/bookings/{booking}/reject', [ProviderBookingManagementController::class, 'reject']);
        });

        /** --- Admin --- */
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);
            Route::get('/analytics', [AdminDashboardController::class, 'analytics']);
            Route::apiResource('users', UserManagementController::class);
            Route::get('/users/{user}/activities', [UserManagementController::class, 'activities']);
            Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus']);
        });
    });
});