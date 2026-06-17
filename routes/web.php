<?php

use App\Events\MessageSent;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Models\FeedbackOrder;
use App\Models\User;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::post('/takeover-confirm', [AuthenticatedSessionController::class, 'doTakeover'])->name('do-takeover');

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

Route::prefix('admin/wallet')->group(function () {
    Route::get('/bulk-credit', [WalletController::class, 'showAdminCreditForm'])
        ->name('admin.wallet.bulk-credit.form');

    Route::post('/bulk-credit', [WalletController::class, 'adminBulkCredit'])
        ->name('admin.wallet.bulk-credit.store');
});

Route::get('/fetch-team-members', function () {
    try {
        $roleId = request()->get('role_id');
        $teamMembers = User::where('role_id', $roleId)->select('id', 'name')->get();

        return response()->json(['teamMembers' => $teamMembers]);
    } catch (\Exception $e) {
        \Log::error('Error fetching team members: ' . $e->getMessage());

        return response()->json(['error' => 'Internal Server Error'], 500);
    }
});

Route::get('/fetchFeedback', function () {
    return response()->json(FeedbackOrder::all());
})->name('fetchFeedback');

Route::post('/myordersFeedback', function (Request $request) {
    $feedbackOrder = new FeedbackOrder();
    $feedbackOrder->feedback = $request->input('content');
    $feedbackOrder->uid = auth()->id();
    $feedbackOrder->order_Id = $request->input('order_id');
    $feedbackOrder->save();

    return response()->json(['message' => 'Feedback saved successfully'], 200);
})->name('myordersFeedback');

require __DIR__ . '/auth.php';
