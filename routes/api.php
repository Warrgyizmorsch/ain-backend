<?php

use App\Http\Controllers\CareerApplicationController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\PublicLeadOtpController;
use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'receive']);
Route::post('/career-applications', [CareerApplicationController::class, 'store']);

Route::prefix('public/leads')->group(function () {
    Route::post('/send-otp', [PublicLeadOtpController::class, 'sendOtpAndCreateLead']);
    Route::post('/verify-otp', [PublicLeadOtpController::class, 'verifyOtp']);
});
Route::post('/submit-feedback', [FeedbackController::class, 'submitFeedback']);
Route::post('/save-order', [OrderApiController::class, 'store']);