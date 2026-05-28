<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\CreatorController;
use App\Http\Controllers\Api\V1\OpportunityController;
use App\Http\Controllers\Api\V1\OpportunityEventController;
use App\Http\Controllers\Api\V1\ProspectController;
use App\Http\Controllers\Api\V1\SocialAccountController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::post('/auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:60,1')
        ->name('auth.login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        Route::get('/user', fn (Request $request): UserResource => new UserResource($request->user()))
            ->name('user.show');

        Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('campaigns', CampaignController::class);
        Route::post('prospects/{prospect}/approve-as-creator', [ProspectController::class, 'approveAsCreator'])->name('prospects.approve-as-creator');
        Route::post('prospects/{prospect}/approve-as-brand', [ProspectController::class, 'approveAsBrand'])->name('prospects.approve-as-brand');
        Route::apiResource('prospects', ProspectController::class);
        Route::apiResource('creators', CreatorController::class);
        Route::post('opportunities/{opportunity}/accept', [OpportunityController::class, 'accept'])->name('opportunities.accept');
        Route::get('opportunities/{opportunity}/events', [OpportunityEventController::class, 'index'])->name('opportunities.events.index');
        Route::post('opportunities/{opportunity}/events', [OpportunityEventController::class, 'store'])->name('opportunities.events.store');
        Route::apiResource('opportunities', OpportunityController::class);
        Route::get('creators/{creator}/social-accounts', [SocialAccountController::class, 'creatorIndex'])->name('creators.social-accounts.index');
        Route::get('brands/{brand}/social-accounts', [SocialAccountController::class, 'brandIndex'])->name('brands.social-accounts.index');
        Route::apiResource('social-accounts', SocialAccountController::class);
    });
});
