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
use App\Http\Controllers\Api\ServicePageApiController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\SampleApiController;
use App\Http\Controllers\Api\WriterApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\ExpertApiController;




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

Route::middleware('api.key')->group(function () {
    // Dynamic Service Pages
    Route::get('/service-pages', [ServicePageApiController::class, 'index']);
    Route::get('/service-pages/prefix/{prefix}', [ServicePageApiController::class, 'getByPrefix'])->where('prefix', '.*');
    Route::get('/service-pages/{slug}', [ServicePageApiController::class, 'show'])->where('slug', '.*');

    // Blogs APIs
    Route::get('/blogs', [BlogApiController::class, 'index']);
    Route::get('/blogs/{slug}', [BlogApiController::class, 'show'])->where('slug', '.*');

    // Samples APIs
    Route::get('/samples', [SampleApiController::class, 'index']);
    Route::get('/samples/{slug}', [SampleApiController::class, 'show'])->where('slug', '.*');

    // Writer APIs
    Route::get('/writer-list', [WriterApiController::class, 'index']);
    Route::get('/writer-details/{id?}', [WriterApiController::class, 'show']);

    // FAQ APIs
    Route::get('/faqs', [FaqApiController::class, 'index']);
    Route::get('/faqs/{slug}', [FaqApiController::class, 'show'])->where('slug', '.*');

    // Review APIs
    Route::get('/reviews', [ReviewApiController::class, 'index']);
    Route::get('/reviews/{id}', [ReviewApiController::class, 'show']);

    // Expert APIs
    Route::get('/experts', [ExpertApiController::class, 'index']);
    Route::get('/experts/{idOrSlug}', [ExpertApiController::class, 'show'])->where('idOrSlug', '.*');
});

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
    Route::post('/apply-coupon', [OrderApiController::class, 'applyCoupon']);
    Route::get('/coupons', [OrderApiController::class, 'couponList']);
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
    Route::get('/refer-list', [AuthController::class, 'referList']);
    Route::post('/submit-feedback', [OrderApiController::class, 'submitAppFeedback']);
});


