<?php

use App\Http\Controllers\CareerApplicationController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\PublicLeadOtpController;
use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppDropdownController;
use App\Http\Controllers\Api\AuthController;




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

Route::prefix('app')->group(function () {
    Route::get('/countries', [AppDropdownController::class, 'countries']);
    Route::get('/services', [AppDropdownController::class, 'services']);
    Route::get('/subjects', [AppDropdownController::class, 'subjects']);
    Route::get('/urgencies', [AppDropdownController::class, 'urgencies']);
    Route::get('/word-count', [AppDropdownController::class, 'wordCount']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->prefix('app')->group(function () {
    Route::post('/place-order', [OrderApiController::class, 'placeOrder']);
    Route::get('/order-list', [OrderApiController::class, 'orderList']);
    Route::get('/profile', [AuthController::class, 'profile']);
     Route::post('/profile-update', [AuthController::class, 'updateProfile']);
     Route::post('/raise-ticket', [OrderApiController::class, 'raiseTicket']);
     Route::get('/ticket-details', [OrderApiController::class, 'getTicket']);
      Route::get('/get-ticket', [OrderApiController::class, 'userTickets']);
      Route::get('/wallet-amount', [OrderApiController::class, 'walletAmount']);
      Route::get('/wallet-history', [OrderApiController::class, 'walletTransactions']);
});