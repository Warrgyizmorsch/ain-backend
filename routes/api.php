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
use App\Http\Controllers\Api\SubjectApiController;
use App\Http\Controllers\Api\SubjectPageApiController;




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

    // Subject list APIs
    Route::get('/subjects', [SubjectApiController::class, 'index']);

    // Dynamic Subject Page APIs
    Route::get('/subject-pages', [SubjectPageApiController::class, 'index']);
    Route::get('/subject-pages/{slug}', [SubjectPageApiController::class, 'show'])->where('slug', '.*');
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

// Temporary Route to seed testing data to database (can be run on live staging database)
Route::get('/seed-test-data', function () {
    try {
        // 1. Ensure Prefix/Subjects exist
        $serviceSubject = \App\Models\Subject::firstOrCreate(['slug' => 'service'], [
            'name' => 'service',
            'is_active' => true,
        ]);

        $subjectSubject = \App\Models\Subject::firstOrCreate(['slug' => 'subject'], [
            'name' => 'subject',
            'is_active' => true,
        ]);

        // 2. Clear old dynamic pages
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\ServicePage::truncate();
        \App\Models\SubjectPage::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Ensure some Experts and Reviews exist
        $expertIds = \App\Models\Experts::take(2)->pluck('id')->toArray();
        if (empty($expertIds)) {
            $expert1 = \App\Models\Experts::create([
                'name' => 'Dr. John Doe',
                'finish_order' => 150,
                'inprogress_order' => 5,
                'subject' => 'Mathematics',
                'service' => 'Assignment Help',
                'location' => 'United Kingdom',
                'content' => 'Dr. John Doe is a PhD in Mathematics.',
                'image' => 'https://via.placeholder.com/150',
                'skills' => ['Algebra', 'Calculus'],
                'helpus' => ['Math Homework'],
                'meta_tag' => 'Math Expert',
                'meta_description' => 'Math expert'
            ]);
            $expertIds = [$expert1->id];
        }

        $reviewIds = \App\Models\Review::take(2)->pluck('id')->toArray();
        if (empty($reviewIds)) {
            $r1 = \App\Models\Review::create([
                'id' => 1,
                'name' => 'Liam G.',
                'deadline' => '2026-05-10',
                'submission_date' => '2026-05-09',
                'services_type' => 'Assignment Help',
                'location' => 'Manchester, UK',
                'customer_rating' => 5,
                'description' => 'Excellent math assignment help!'
            ]);
            $reviewIds = [$r1->id];
        }

        // 4. Create Service Pages
        $servicePagesData = [
            [
                'subject_id' => $serviceSubject->id,
                'slug' => 'service/assignment',
                'meta_title' => 'Best Assignment Writing Help UK | 40% OFF',
                'meta_description' => 'Looking for top-notch assignment writing help in the UK?',
                'hero_heading' => 'Assignment Writing Help UK',
                'hero_highlight' => '100% Plagiarism-Free & On-Time',
                'hero_content' => '<p>Writing assignments can be challenging.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ],
            [
                'subject_id' => $serviceSubject->id,
                'slug' => 'service/assignment/english',
                'meta_title' => 'English Assignment Help UK',
                'meta_description' => 'Get expert help with English literature.',
                'hero_heading' => 'English Assignment Help',
                'hero_highlight' => 'Master English Literature',
                'hero_content' => '<p>Struggling with Shakespeare?</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ],
            [
                'subject_id' => $serviceSubject->id,
                'slug' => 'service/assignment/economics',
                'meta_title' => 'Economics Assignment Help UK',
                'meta_description' => 'Get microeconomics and macroeconomics help.',
                'hero_heading' => 'Economics Assignment Help',
                'hero_highlight' => 'Micro & Macroeconomics Help',
                'hero_content' => '<p>From supply and demand graphs to macro models.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ],
            [
                'subject_id' => $serviceSubject->id,
                'slug' => 'service/dissertation',
                'meta_title' => 'Professional Dissertation Writing Services UK',
                'meta_description' => 'Get dissertation writing assistance.',
                'hero_heading' => 'Dissertation Writing Services UK',
                'hero_highlight' => 'PhD Academic Experts',
                'hero_content' => '<p>Writing a dissertation can be overwhelming.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ],
            [
                'subject_id' => $serviceSubject->id,
                'slug' => 'service/dissertation/literature-review',
                'meta_title' => 'Literature Review Dissertation Help',
                'meta_description' => 'Critical literature reviews.',
                'hero_heading' => 'Dissertation Literature Review Help',
                'hero_highlight' => 'Theoretical Framework',
                'hero_content' => '<p>Critical analysis of sources.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ]
        ];

        foreach ($servicePagesData as $pageData) {
            $fullData = array_merge([
                'meta_title' => 'Default Meta Title',
                'meta_description' => 'Default Meta Description',
                'hero_heading' => 'Default Hero Heading',
                'hero_content' => '<p>Default Hero Content</p>',
                'expert_ids' => [],
                'review_ids' => [],
                'faqs' => [],
                'why_items' => [],
                'is_published' => true,
            ], $pageData);
            \App\Models\ServicePage::create($fullData);
        }

        // 5. Create Subject Pages
        $subjectPagesData = [
            [
                'subject_id' => $subjectSubject->id,
                'slug' => 'subject/maths',
                'meta_title' => 'Math Assignment Help UK',
                'meta_description' => 'Get math assignment solutions.',
                'hero_heading' => 'Math Assignment Help',
                'hero_highlight' => 'Detailed Solutions',
                'hero_content' => '<p>Solve complex math problems.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ],
            [
                'subject_id' => $subjectSubject->id,
                'slug' => 'subject/chemistry',
                'meta_title' => 'Chemistry Assignment Help UK',
                'meta_description' => 'Chemistry assignment help.',
                'hero_heading' => 'Chemistry Assignment Help',
                'hero_highlight' => 'Formulas and Equations',
                'hero_content' => '<p>Organic and inorganic chemistry help.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ],
            [
                'subject_id' => $subjectSubject->id,
                'slug' => 'subject/history',
                'meta_title' => 'History Assignment Help UK',
                'meta_description' => 'History essays and reports.',
                'hero_heading' => 'History Assignment Help',
                'hero_highlight' => 'World History & Modern History',
                'hero_content' => '<p>Accurate history research papers.</p>',
                'is_published' => true,
                'expert_ids' => $expertIds,
                'review_ids' => $reviewIds,
            ]
        ];

        foreach ($subjectPagesData as $pageData) {
            $fullData = array_merge([
                'meta_title' => 'Default Meta Title',
                'meta_description' => 'Default Meta Description',
                'hero_heading' => 'Default Hero Heading',
                'hero_content' => '<p>Default Hero Content</p>',
                'expert_ids' => [],
                'review_ids' => [],
                'faqs' => [],
                'why_items' => [],
                'is_published' => true,
            ], $pageData);
            \App\Models\SubjectPage::create($fullData);
        }

        return response()->json(['status' => 'success', 'message' => 'Staging database successfully seeded with test pages!']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
});


