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

Route::get('/debug-db', function () {
    try {
        $menus = \Illuminate\Support\Facades\DB::table('menu')->get();
        return response()->json($menus);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/fix-parent-id', function () {
    try {
        $affected = \Illuminate\Support\Facades\DB::table('menu')
            ->where('parent_id', 43)
            ->update(['parent_id' => 44]);
        return response()->json([
            'status' => 'success',
            'affected_rows' => $affected
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
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

// Email Integration Routes (WhatsApp-like Inbox, Composer, Drafts, Sync)
Route::prefix('emails')->name('emails.')->middleware(['auth', 'check.permission'])->group(function () {
    Route::get('/', [\App\Http\Controllers\EmailController::class, 'index'])->name('index');

    // Email Campaigns Module (Placed before /{id} so it is never shadowed)
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [\App\Http\Controllers\EmailCampaignController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\EmailCampaignController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\EmailCampaignController::class, 'store'])->name('store');
        Route::post('/audience-count', [\App\Http\Controllers\EmailCampaignController::class, 'audienceCount'])->name('audience-count');
        Route::post('/upload-image', [\App\Http\Controllers\EmailCampaignController::class, 'uploadImage'])->name('upload-image');
        Route::get('/{id}', [\App\Http\Controllers\EmailCampaignController::class, 'show'])->name('show');
        Route::post('/{id}/send-now', [\App\Http\Controllers\EmailCampaignController::class, 'sendNow'])->name('send-now');
        Route::post('/{id}/pause', [\App\Http\Controllers\EmailCampaignController::class, 'pause'])->name('pause');
        Route::post('/{id}/resume', [\App\Http\Controllers\EmailCampaignController::class, 'resume'])->name('resume');
        Route::delete('/{id}', [\App\Http\Controllers\EmailCampaignController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('checkrole:1')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\EmailController::class, 'settings'])->name('settings');
        Route::post('/settings/store', [\App\Http\Controllers\EmailController::class, 'storeConfiguration'])->name('settings.store');
        Route::post('/settings/{id}/update', [\App\Http\Controllers\EmailController::class, 'updateConfiguration'])->name('settings.update');
        Route::post('/settings/{id}/clone', [\App\Http\Controllers\EmailController::class, 'cloneConfiguration'])->name('settings.clone');
        Route::delete('/settings/{id}/delete', [\App\Http\Controllers\EmailController::class, 'deleteConfiguration'])->name('settings.delete');
        Route::post('/settings/{id}/test', [\App\Http\Controllers\EmailController::class, 'testConnection'])->name('settings.test');
    });
    Route::get('/thread/{threadId}', [\App\Http\Controllers\EmailController::class, 'getThread'])->name('thread');
    Route::post('/send', [\App\Http\Controllers\EmailController::class, 'send'])->name('send');
    Route::post('/draft', [\App\Http\Controllers\EmailController::class, 'saveDraft'])->name('draft');
    Route::post('/toggle-star', [\App\Http\Controllers\EmailController::class, 'toggleStar'])->name('star');
    Route::post('/mark-read', [\App\Http\Controllers\EmailController::class, 'markAsRead'])->name('mark-read');
    Route::post('/delete', [\App\Http\Controllers\EmailController::class, 'deleteMessage'])->name('delete');
    Route::post('/archive', [\App\Http\Controllers\EmailController::class, 'archive'])->name('archive');
    Route::post('/move-folder', [\App\Http\Controllers\EmailController::class, 'moveToFolder'])->name('move-folder');
    Route::post('/undo', [\App\Http\Controllers\EmailController::class, 'undoAction'])->name('undo');
    Route::post('/bulk-labels', [\App\Http\Controllers\EmailController::class, 'bulkAssignLabels'])->name('bulk-labels');
    Route::post('/sync', [\App\Http\Controllers\EmailController::class, 'sync'])->name('sync');
    Route::get('/updates', [\App\Http\Controllers\EmailController::class, 'updates'])->name('updates');
    Route::get('/csrf-token', [\App\Http\Controllers\EmailController::class, 'csrfToken'])->name('csrf-token');
    Route::get('/attachment/{id}/download', [\App\Http\Controllers\EmailController::class, 'downloadAttachment'])->name('attachment.download');
    Route::get('/attachment/{id}/view', [\App\Http\Controllers\EmailController::class, 'viewAttachment'])->name('attachment.view');
    Route::get('/attachment/{id}/preview-html', [\App\Http\Controllers\EmailController::class, 'previewAttachmentHtml'])->name('attachment.preview-html');
    Route::get('/search/suggest', [\App\Http\Controllers\EmailController::class, 'suggestSearch'])->name('search.suggest');
    Route::get('/{id}', [\App\Http\Controllers\EmailController::class, 'show'])->where('id', '[0-9]+')->name('show');
    Route::post('/{id}/star', [\App\Http\Controllers\EmailController::class, 'toggleStarById'])->name('star.id');
    Route::delete('/{id}', [\App\Http\Controllers\EmailController::class, 'deleteById'])->name('delete.id');
    Route::post('/{id}/reply', [\App\Http\Controllers\EmailController::class, 'replyToMessage'])->name('reply.id');
    Route::post('/labels/save', [\App\Http\Controllers\EmailController::class, 'saveThreadLabels'])->name('labels.save');
    Route::get('/contacts/suggest', [\App\Http\Controllers\EmailController::class, 'suggestRecipients'])->name('contacts.suggest');
    Route::get('/templates', [\App\Http\Controllers\EmailController::class, 'getTemplates'])->name('templates.list');
    Route::post('/templates/save', [\App\Http\Controllers\EmailController::class, 'saveTemplate'])->name('templates.save');
    Route::delete('/templates/{id}', [\App\Http\Controllers\EmailController::class, 'deleteTemplate'])->name('templates.delete');
});

// Email Campaign Public Tracking (Open Pixel & Click Redirect)
Route::get('/email/track/open/{token}', [\App\Http\Controllers\EmailCampaignController::class, 'trackOpen'])->name('emails.campaigns.track.open');
Route::get('/email/track/click/{token}', [\App\Http\Controllers\EmailCampaignController::class, 'trackClick'])->name('emails.campaigns.track.click');
Route::post('/webhook/emails/inbound', [\App\Http\Controllers\EmailController::class, 'webhook'])
    ->middleware('throttle:60,1')
    ->name('emails.webhook');
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
