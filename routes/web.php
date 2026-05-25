<?php

use App\Http\Controllers\MasterController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\HomeController;
use App\Models\Add;
use App\Models\Blog;
use App\Models\User;
use App\Models\Sample;
use App\Models\FeedbackOrder;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\FaqUrlController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Experts; 
use App\Models\Review;
use App\Models\WhatsappMessage;
use App\Events\MessageSent;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Services\SchemaService;
use App\Http\Controllers\UkAssignmentController;
use Illuminate\Support\Facades\DB;

Route::post('/takeover-confirm', [AuthenticatedSessionController::class, 'doTakeover'])->name('do-takeover');


Route::get('/load-more-blogs', [BlogController::class, 'loadMore']);
Route::post('/contact-us/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/click2call', function () {
    return view('api.clic2call');
});
Route::get('/test-broadcast', function () {


    $msg = WhatsappMessage::create([
        'name' => 'yash',
        'phone' => '919664100138',
        'message' => 'Test Real-time Broadcast',
        'direction' => 'inbound',
        'wa_message_id' => '!dsfsdfklsdhflsjkdhf9876',
    ]);

    event(new MessageSent($msg));
    return 'Broadcast triggered';
});



Route::post('/sendsms', [ChatController::class, 'send'])->name('send-whatsapp');
Route::get('/chat/{phone?}', [ChatController::class, 'showChat'])->name('chat');
Route::post('/writer-login', [UserController::class, 'Login']);
Route::post('/neworder-fromhome', [LeadsController::class, 'FrontEndLeads'])->name('neworder.create');
Route::post('/placeNewOrder', [LeadsController::class, 'FrontEndLeadsNew']);
Route::get('/get-files-by-order', [LeadsController::class, 'findfiles'])->name('get-files-by-order');
Route::get('thank-you', [HomeController::class, 'thankyou'])->name('thank-you');


Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
Route::post('/wallet/verify', [WalletController::class, 'verify'])->name('wallet.verify');
Route::prefix('admin/wallet')->group(function () {
    Route::get('/bulk-credit', [WalletController::class, 'showAdminCreditForm'])
        ->name('admin.wallet.bulk-credit.form');

    Route::post('/bulk-credit', [WalletController::class, 'adminBulkCredit'])
        ->name('admin.wallet.bulk-credit.store');
});

Route::get('/', function () {
   return redirect('/login');
});


Route::get(
    '/Offers',
    function () {
        $data['title'] = 'Offers - Assignment In Need';
        $data['description'] = '';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/Offers';
        return view('frontend.offers', compact('data'));
    }
);

Route::get(
    '/PrivacyPolicy',
    function () {
        $data['title'] = 'Privacy Policy | Assignment In Need | Secure UK Student Data';
        $data['description'] = 'Read the official Privacy Policy of Assignment In Need. We ensure 100% confidentiality and UK GDPR compliance. Your academic data and identity are always protected.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/PrivacyPolicy';
        return view('Policy.PrivacyPolicy', compact('data'));
    }
);
Route::get(
    '/Terms-Conditions',
    function () {
        $data['title'] = 'Terms and Conditions | Assignment In Need | Official Service Terms';
        $data['description'] = 'View the official Terms and Conditions for Assignment In Need. Understand your rights, our service obligations, and the legal framework for our UK academic assistance.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/Terms-Conditions';
        return view('Policy.Terms&Conditions', compact('data'));
    }
);
Route::get(
    '/RefundPolicy',
    function () {
        $data['title'] = 'Refund Policy | Assignment In Need | Our Money-Back Guarantee';
        $data['description'] = 'Transparent and fair Refund Policy for UK students. Learn about our money-back guarantee, eligibility criteria, and how we protect your payments at Assignment In Need.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/RefundPolicy';
        return view('Policy.RefundPolicy', compact('data'));
    }
);
Route::get(
    '/GuaranteedPolicy',
    function () {
        $data['title'] = 'Guaranteed Policy | Assignment In Need | Quality & Success Assured';
        $data['description'] = 'Discover our service guarantees. From plagiarism-free reports to timely delivery, learn how Assignment In Need guarantees high-quality results for every UK student.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/GuaranteedPolicy';
        return view('Policy.GuaranteedPolicy', compact('data'));
    }
);
Route::get(
    '/CancellationPolicy',
    function () {
        $data['title'] = 'Cancellation Policy | Assignment In Need | Order Update Rules';
        $data['description'] = 'Need to cancel an order? Read the Assignment In Need Cancellation Policy to understand timelines, refund eligibility, and how to manage your UK assignment requests.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/CancellationPolicy';
        return view('Policy.CancellationPolicy', compact('data'));
    }
);

Route::get('/MyOrders', [OrderController::class, 'myOrder'])->middleware(['auth'])->name('MyOrders');


// Route::get('/samples', function () {return view('frontend/header/samples');});
Route::get(
    '/upload-your-assignment',
    function () {
        $data['title'] = 'Order Now- Assignment Help- 40% Off & Free CV- Assignment In Need';
        $data['description'] = 'Get expert assignment help by placing your order now at Assignment In Need. We provide reliable and top-quality academic support for students in all Subjects.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/upload-your-assignment';

        $countries = DB::table('countries')->orderBy('name')->get();
        return view('frontend/header/order-now-2', compact('data','countries'));
    }
);
Route::post('/upload-new-order', [OrderController::class, 'submit'])->name('upload-new-order');
Route::post('/mini-new-order', [OrderController::class, 'submitMiniQuote'])->name('mini-new-order');

Route::post('/upload-from-order-now', [OrderController::class, 'submitWithOrderNowPage'])->name('upload-from-order-now');

Route::get(
    'contact-us',
    function () {
        $data['title'] = 'Contact us Today For All Types Of Assignment- Assignment In Need';
        $data['description'] = 'Contact Assignment In Need today for expert assistance with all types of assignments. Our experienced team is ready to provide high-quality academic Support.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/contact-us';
        return view('frontend/header/contact-us', compact('data'));
    }
);

Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'getBlogBySlug']);
Route::get('/blog-sitemap', [SitemapController::class, 'blogSitemap'])->name('blog-sitemap');
Route::get('/sample-sitemap', [SitemapController::class, 'freeSampleSitemap'])->name('sample-sitemap');
Route::get('free-samples', [SampleController::class, 'indexpage'])->name('free-samples');
Route::get('free-samples/{title}', [SampleController::class, 'categoryDeatails'])->name('free-samples/title');
Route::get('free-samples/{title}/{subject}', [SampleController::class, 'sampleDeatails'])->name('free-samples/title/subject');
Route::get('downloads-sample/{slug}', [SampleController::class, 'downloadSample'])->name('downloads-sample/slug');
// Route::get('/faqs/{slug}', [FaqUrlController::class, 'getfaqBySlug']);

Route::get('/faqs', [FaqUrlController::class, 'faqs']);
Route::get('/review', [HomeController::class, 'review']);
Route::get('/reviews/load-more', [HomeController::class, 'loadMoreReviews']);
Route::get('/writers', [ExpertController::class, 'expert'])->name('writers');
Route::get('/writers/{slug}', [ExpertController::class, 'expertProfile'])->name('writers.profile');
Route::get('/load-experts', [ExpertController::class, 'loadExperts']);
// Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');



Route::get('/fetch-team-members', function () {
    try {
        $roleId = request()->get('role_id');
        $teamMembers = User::where('role_id', $roleId)->get();
        return response()->json(['teamMembers' => $teamMembers]);
    } catch (\Exception $e) {
        \Log::error('Error fetching team members: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
});

Route::get('/fetchFeedback', function () {
    $feedback = FeedbackOrder::all();
    return response()->json($feedback);
})->name('fetchFeedback');

Route::post('/myordersFeedback', function (Request $request) {
    $content = $request->input('content');
    $userId = auth()->id();
    $orderId = $request->input('order_id');
    $feedbackOrder = new FeedbackOrder();
    $feedbackOrder->feedback = $content;
    $feedbackOrder->uid = $userId;
    $feedbackOrder->order_Id = $orderId;
    $feedbackOrder->save();
    return response()->json(['message' => 'Feedback saved successfully'], 200);
})->name('myordersFeedback');
Route::get(
    'myProfile',
    function () {
        $user = Auth::user();
        if ($user) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'mobile_no' => $user->mobile_no,
                'photo' => $user->photo,
                'Id' => $user->id,
            ];
        } else {
            $userData = null;
        }
        $data['title'] = 'My Profile | Online Essay, Research Paper writers UK';
        $data['description'] = 'If you are looking for Online Assignment Help UK then Assignment In Need is one of the best Online Essay, Research Paper writers UK.';
        $data['keyword'] = '';
        return view('frontend.myProfile', compact('data', 'userData'));
    }
);
Route::post('/change-user-password', function () {
    $userId = Auth::id();
    $payload = request()->all();
    $user = User::find($userId);
    if (empty($payload['current_password']) || empty($payload['new_password']) || empty($payload['new_password_confirmation'])) {
        return Redirect::back()->with('error', 'password cannot be empty');
    }
    if ($payload['new_password'] !== $payload['new_password_confirmation']) {
        return Redirect::back()->with('error', 'New password and confirmation do not match');
    }
    if (Hash::check($payload['current_password'], $user->password)) {
        $hashedNewPassword = Hash::make($payload['new_password']);
        $user->update(['password' => $hashedNewPassword]);
        return Redirect::back()->with('success', 'Password updated successfully');
    } else {
        return Redirect::back()->with('error', 'Current password is incorrect');
    }
})->name('postChangeUserPassword');

Route::post('/userProfile/{id}', function ($id, Request $request) {
    try {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile_no = $request->input('phone');
        $user->address = $request->input('address');
        if ($request->hasFile('photo')) {
            $uploadedFile = $request->file('photo');
            $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $destinationPath = public_path('assets/media/avatars');
            $uploadedFile->move($destinationPath, $fileName);
            $user->photo = 'assets/media/avatars/' . $fileName;
        } else {
            $user->photo = 'assets/media/avatars/blank.png';
        }
        $user->save();
        return redirect()->back()->with('success', 'Profile Updated Successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
})->name('userProfile.update');

Route::get('/feedbacks', function () {
    return view('feedbacks');
});

require __DIR__ . '/auth.php';
