<?php

use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\CreatorController;
use App\Http\Controllers\Api\V1\CreatorLeadController;
use App\Http\Controllers\Api\V1\SocialAccountController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', fn (Request $request): UserResource => new UserResource($request->user()))
        ->name('user.show');

    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('campaigns', CampaignController::class);
    Route::post('creator-leads/{creator_lead}/approve', [CreatorLeadController::class, 'approve'])->name('creator-leads.approve');
    Route::apiResource('creator-leads', CreatorLeadController::class);
    Route::apiResource('creators', CreatorController::class);
    Route::get('creators/{creator}/social-accounts', [SocialAccountController::class, 'creatorIndex'])->name('creators.social-accounts.index');
    Route::get('brands/{brand}/social-accounts', [SocialAccountController::class, 'brandIndex'])->name('brands.social-accounts.index');
    Route::apiResource('social-accounts', SocialAccountController::class);
});
