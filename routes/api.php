<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{HealthController,ProductApiController,InventoryApiController,ReportApiController};

Route::prefix('v1')->name('api.v1.')->group(function(){
    Route::get('/health', HealthController::class)->name('health');
    Route::middleware(['api.key'])->group(function(){
        Route::get('/me', fn(\Illuminate\Http\Request $request) => response()->json(['data'=>['api_key_id'=>$request->attributes->get('apiKey')->id,'user_id'=>$request->attributes->get('apiKey')->user_id,'abilities'=>$request->attributes->get('apiKey')->abilities ?? []]]));
        Route::middleware('api.ability:products:read')->get('/products',[ProductApiController::class,'index']);
        Route::middleware('api.ability:products:read')->get('/products/{product}',[ProductApiController::class,'show']);
        Route::middleware('api.ability:inventory:read')->get('/inventory',[InventoryApiController::class,'index']);
        Route::middleware('api.ability:reports:read')->get('/reports/summary',[ReportApiController::class,'summary']);
        Route::middleware('idempotency')->group(function(){
            // Mutating API endpoints will be added here as transactional services are exposed.
        });
    });
});
