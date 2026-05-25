<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FaqUrlController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewFaqController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupMasterController;
use App\Models\Categories;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrimeassimentController;
use Illuminate\View\Concerns\ManagesFragments;
use App\Http\Controllers\UserHistoryController; 

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

});
Route::post('/lead/export', [LeadsController::class, 'export'])->name('lead.export');
Route::get('/lead/export/status', [LeadsController::class, 'exportStatus']);
Route::post('/order/export', [OrderController::class, 'export'])->name('order.export');
Route::get('/order/export/status', [OrderController::class, 'exportStatus']);
Route::post('/lead/assign-type', [LeadsController::class, 'assignType'])
    ->name('lead.assignType');
Route::post('/lead-reason-update', [LeadsController::class, 'updateLeadReason'])
    ->name('lead-reason-update');

// Route::middleware(['auth', 'check.permission'])->group(function () {
Route::middleware(['auth'])->group(function () {

    Route::prefix('career')->group(function () {
        Route::get('/', [CareerController::class, 'index'])->name('career.index');

        // 👇 Update route for modal
        Route::put('/update/{id}', [CareerController::class, 'update'])
            ->name('career.applications.update');
    });

    // Setting Managment
    Route::get('/menus', [MenuController::class, 'index'])->name('menus');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus');
    Route::delete('menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('update');

    Route::get('/submenu', [MenuController::class, 'submenu'])->name('submenu');
    Route::post('/submenu', [MenuController::class, 'submenu_insert'])->name('submenu');
    Route::delete('submenu/{submenus}', [MenuController::class, 'delete'])->name('submenu.delete');
    Route::put('/submenu/{id}', [MenuController::class, 'submenu_update'])->name('submenu_update');

    Route::get('/userright', [MenuController::class, 'userright'])->name('userright');
    Route::Post('/userright', [MenuController::class, 'permission'])->name('userright');
    Route::get('/rolePermission', [MenuController::class, 'rolePermission'])->name('rolePermission');
    Route::post('/updateUser/{id}', [LeadsController::class, 'updateUser']);


    // User Managment
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::POST('/user/{id}', [UserController::class, 'EditUser'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'DeleteUser'])->name('user.delete');
    Route::get('/usercreate', [UserController::class, 'new_user'])->name('usercreate');
    Route::Post('/usercreate', [UserController::class, 'insert_new_user'])->name('usercreate');
    Route::post('/change-passwordby-admin', [ProfileController::class, 'changePasswordSaveAdmin'])->name('postChangePasswordAdmin');
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/change-password', [ProfileController::class, 'changePasswordSave'])->name('postChangePassword');
    Route::POST('/profile/{id}', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/user-logs', [UserController::class, 'userLogs'])->name('user.userlogs');
    Route::get('/user-history/{userId?}', [UserHistoryController::class, 'index'])->name('user.feedbackhistory');


    // Start master managment
    Route::get('/master', [MasterController::class, 'index'])->name('master');
    Route::get('/failedJobs', [MasterController::class, 'failedJobs'])->name('failedJobs');

    // Type-Of Services
    Route::get('/typeOfSecvices', [MasterController::class, 'typeOfSecvices'])->name('typeOfSecvices');
    Route::Post('/typeOfSecvices', [MasterController::class, 'insert_services'])->name('typeOfSecvices');
    Route::put('/typeOfSecvices/{id}', [MasterController::class, 'update_service'])->name('typeOfSecvices.update');
    Route::delete('/typeOfSecvices/{id}', [MasterController::class, 'Delete_service'])->name('typeOfSecvices.delete');

    // Type Of Paper
    route::get('/typeofpaper', [MasterController::class, 'paperType'])->name('typeofpaper');
    route::post('/typeofpaper', [MasterController::class, 'insert_paper'])->name('typeofpaper');
    Route::put('/typeofpaper/{id}', [MasterController::class, 'paper_update'])->name('typeofpaper.update');
    Route::delete('/typeofpaper/{id}', [MasterController::class, 'delete_paper'])->name('typeofpaper.delete');


    // Formatting
    Route::get('/formatting', [MasterController::class, 'formatting'])->name('formatting');
    Route::post('/formatting', [MasterController::class, 'formatting_insert'])->name('formatting');
    Route::put('/formatting/{id}', [MasterController::class, 'formatting_update'])->name('formatting.update');
    Route::delete('/formatting/{id}', [MasterController::class, 'formatting_delete'])->name('formatting.delete');

    // Categories
    Route::get('/Categories', [MasterController::class, 'Categories'])->name('Categories');
    Route::post('/categories', [MasterController::class, 'store_categories'])->name('categories.store');
    Route::Put('/categories/{id}', [MasterController::class, 'update_categories'])->name('categories.update');
    Route::delete('/categories/{id}', [MasterController::class, 'delete_categories'])->name('categories.delete');

    // Source Master
    Route::get('/sources', [MasterController::class, 'sources'])->name('sources');
    Route::post('/sources', [MasterController::class, 'store_source'])->name('sources.store');
    Route::put('/sources/{id}', [MasterController::class, 'update_source'])->name('sources.update');
    Route::delete('/sources/{id}', [MasterController::class, 'delete_source'])->name('sources.delete');

    // College Managment Route
    Route::get('/college', [MasterController::class, 'college'])->name('college');
    Route::post('/college', [MasterController::class, 'store_college'])->name('college.store');
    Route::put('/college/{id}', [MasterController::class, 'college_update'])->name('college.update');
    Route::delete('/college/{id}', [MasterController::class, 'delete_college'])->name('college.delete');
    Route::post('/status/{id}', [OrderController::class, 'status_update'])->name('status.update');

    // Bank 
    Route::get('/Banks', [MasterController::class, 'Banks'])->name('Banks');
    Route::post('/banks', [MasterController::class, 'store_bank'])->name('banks.store');
    Route::put('/banks/{id}/edit', [MasterController::class, 'edit'])->name('banks.edit');
    Route::delete('/banks/{id}', [MasterController::class, 'destroy'])->name('banks.destroy');

    // Project Status 
    Route::get('/status', [MasterController::class, 'status'])->name('status');

    // All Pamyent Data
    Route::get('/Payments', [MasterController::class, 'Payments'])->name('Payments');
    Route::post('/update-payment-status/bulk', [MasterController::class, 'bulkUpdateStatus'])->name('payments.bulkUpdateStatus');
    Route::post('/Payments', [MasterController::class, 'update_payments'])->name('update_payments');
    Route::delete('/Payments/{id}', [MasterController::class, 'delete_payments'])->name('delete_payments');


    // Writer Managment
    Route::get('/writer', [MasterController::class, 'Writer'])->name('writer');
    Route::POst('/writer', [MasterController::class, 'store_writer'])->name('writer');
    Route::Put('/writer/{id}', [MasterController::class, 'update_writer'])->name('writer.update');
    Route::delete('/writer/{id}', [MasterController::class, 'delete_writer'])->name('writer.delete');

    Route::get('/writerTL', [MasterController::class, 'writerTL'])->name('writerTL');
    Route::Post('/writerTL', [MasterController::class, 'writerTLinsert'])->name('writerTL');
    Route::PUT('/writerTL/{id}', [MasterController::class, 'update_writerTL'])->name('writerTL.update');
    Route::delete('/writerTL/{id}', [MasterController::class, 'delete_writerTL'])->name('writerTL.delete');


    Route::get('/subwriter', [MasterController::class, 'SubWriter'])->name('subwriter');
    Route::get('/getSubWriters/{writerTLId}', [MasterController::class, 'getSubWriters'])->name('getSubWriters');
    Route::get('/get-writer-tls', [MasterController::class, 'getWriterTLs'])->name('get.writer.tls');
    Route::Post('/subwriter', [MasterController::class, 'store_subwriter'])->name('subwriter');
    Route::Put('/subwriter/{id}', [MasterController::class, 'update_subwriter'])->name('subwriter.update');
    Route::Put('/subwriter/delete/{id}', [MasterController::class, 'delete_subwriter'])->name('subwriter.delete');

    // End Master Managment


    // Order Route Mangement Start
    Route::Get('/order', [OrderController::class, 'index'])->name('order');
    Route::get('/get-data', [OrderController::class, 'get-data'])->name('get');
    Route::put('/order/{id}', [OrderController::class, 'OrderEdit'])->name('order.update');
    Route::put('/fail/{orderId}', [OrderController::class, 'fail'])->name('update.order');
    Route::put('/update-status/{id}', [OrderController::class, 'updateOrderStatus'])->name('order.update-status');
    route::put('/payment/{id}', [OrderController::class, 'payment'])->name('payment.update');
    route::get('/search', [OrderController::class, 'search']);
    route::get('/suggestions', [UserController::class, 'search'])->name('suggestions');
    Route::get('/fetch-subwriters', [OrderController::class, 'fetchSubWriters'])->name('fetch-subwriters');
    Route::get('/search-order', [SearchController::class, 'search'])->name('search-order');
    Route::get('/orderpayments/{order}/{payment}', [OrderController::class, 'orderPaymentwithData'])->name('orderpayments.edit');
    Route::put('/payment/updateM/{id}', [OrderController::class, 'dataUpdate'])->name('payment.updateM');
    Route::Post('/order/feedback', [OrderController::class, 'saveFeedback'])->name('feedback');
    route::get('/search-writer', [OrderController::class, 'search_writer']);
    route::get('/search-writerTl', [OrderController::class, 'search_writerTl']);
    route::get('/filter', [OrderController::class, 'filter']);
    route::get('edit/{id}', [OrderController::class, 'orderEditPage'])->name('edit');
    route::get('call/{id}', [OrderController::class, 'orderCallPage'])->name('call');
    route::get('comment/{id}', [OrderController::class, 'orderCommentPage'])->name('comment');
    route::get('orderpayments/{id}', [OrderController::class, 'orderPayment'])->name('orderpayments');
    route::delete('orderpayments/{id}', [OrderController::class, 'orderPayment_delete'])->name('orderpayments.delete');
    Route::post('/comment/{id}', [OrderController::class, 'insert_feedback'])->name('comment.insert');
    Route::post('/orderedit/{id}', [OrderController::class, 'OrderEditwriterAdmin'])->name('orderedit.update');


    // Feedback Managmengment Route 
    Route::get('/feedback', [OrderController::class, 'feedbacksheet'])->name('feedback');
    Route::post('/send-feedback', [OrderController::class, 'sendFeedback'])->name('send.feedback');
    Route::post('/mark-as-read', [OrderController::class, 'markAsRead']);
    route::post('/OrderCallInsert.{id}', [OrderController::class, 'OrderCallInsert']);
    Route::post('/swap-user-data', [OrderController::class, 'swapUserData']);
    Route::post('/appdialnumber', [CallController::class, 'initiateCall'])->name('appdialnumber');
    Route::get('/HangUp', [CallController::class, 'HangUp'])->name('HangUp');
    Route::get('/HoldCall', [CallController::class, 'HoldCall'])->name('HoldCall');
    Route::get('/unhold', [CallController::class, 'UnHoldCall'])->name('unhold');

    // Qc Managment Route
    Route::get('/Qc-Sheets', [OrderController::class, 'Qc'])->name('Qc-Sheets');
    Route::post('/qc/{id}', [OrderController::class, 'QcUpdate'])->name('qc.update');
    route::get('/search-qc', [SearchController::class, 'searchQc']);
    route::get('/Qc-edit.{id}', [SearchController::class, 'Qc_edit'])->name('Qc-edit.update');
    Route::post('/update-ai-score/{orderId}', [SearchController::class, 'updateAi']);
    Route::post('/qcchecked/{orderId}', [SearchController::class, 'qcchecked']);

    // Follow Up Magment Route 
    Route::PUT('/followUpUser/{id}', [UserController::class, 'followupuser']);
    Route::get('/follow-up', [OrderController::class, 'followUp'])->name('follow-up');
    Route::post('/follow/{id}', [OrderController::class, 'followUpUpdate'])->name('follow.update');
    Route::get('/search-fw', [SearchController::class, 'searchFw']);


    // Team Allowtment For Order
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::post('/teams/update', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');

    // End Order Mangment  End


    Route::get('/export', [ExportController::class, 'export'])->name('export.orders');
    Route::get('/export-leads', [ExportController::class, 'exportLeads'])->name('export.leads');
    Route::get('/export-users', [ExportController::class, 'exportUsers'])->name('export.users');
    Route::get('/export-WD', [ExportController::class, 'exportWD'])->name('export.WD');

    // Lead Mangment 
    Route::get('/leads-datas', [LeadsController::class, 'leads'])->name('leads-datas');
    Route::get('/c-leads', [LeadsController::class, 'cancelleads'])->name('c-leads');
    Route::put('/cancel_leads{id}', [LeadsController::class, 'leads_Cancel'])->name('leads.update');
    Route::put('/active_leads{id}', [LeadsController::class, 'leads_Active'])->name('leads.update');
    Route::Delete('/Delete{id}', [LeadsController::class, 'delete_leads'])->name('leads.Delete');
    Route::post('/update-lead-status', [LeadsController::class, 'updateLeadReason']);

    Route::post('/update-leads-status', [LeadsController::class, 'updateLeadStatus'])
    ->name('update-leads-status');


    // routes/web.php

    // Lead data management
    Route::post('/swap-lead-mobile', [LeadsController::class, 'swapUserData']);
    Route::get('/leads', [LeadsController::class, 'index'])->name('leads');
    Route::get('/leads-new', [LeadsController::class, 'index2'])->name('leads-new');
    Route::POST('/leads', [LeadsController::class, 'insert_leads'])->name('leads');
    route::get('/search-leads', [LeadsController::class, 'search'])->name('search-leads');
    Route::post('/leads/send-data', [LeadsController::class, 'insert_call'])->name('send-data');
    Route::get('/leads/fetch-data/{id}', [LeadsController::class, 'fetchData'])->name('fetch-data');
    Route::POST('/leads/{id}', [LeadsController::class, 'leads_update'])->name('leads.edit');
    Route::get('/userData', [LeadsController::class, 'userData'])->name('userData');
    Route::POST('/convert/{id}', [LeadsController::class, 'convert'])->name('convert.convert');
    route::get('leadedit.{id}', [LeadsController::class, 'leadEditPage'])->name('leadedit');
    route::get('leadcall.{id}', [LeadsController::class, 'leadCallPage'])->name('leadcall');
    route::post('neworder', [LeadsController::class, 'orderByuser'])->name('neworder');
    route::post('updateEmail/{email}', [LeadsController::class, 'updateEmail'])->name('neworder');
    Route::get('checkEmail', [LeadsController::class, 'checkEmail'])->name('update.email');
    Route::post('/convertleads/{lead}', [LeadsController::class, 'convertLead'])->name('convertLead');
    Route::put('/checklead/{id}', [LeadsController::class, 'checked'])->name('update.value');

    // Review

    Route::get('/review-create',  [HomeController::class, 'review_create']);
    Route::get('/review-list',  [HomeController::class, 'review_list']);
    route::get('/review-edit/{id}',  [HomeController::class, 'review_edit'])->name('review.edit');
    Route::delete('/review/{id}', [HomeController::class, 'destroy'])->name('review.destroy');

    Route::prefix('reviews')->name('new-review.')->group(function () {
        Route::post('/store', [HomeController::class, 'store'])->name('store');
        Route::put('/update/{id}', [HomeController::class, 'update'])->name('update');
    });
    // Expert
    Route::get('/create-expert', [ExpertController::class, 'create'])->name('create-expert');
    Route::get('/create-expert-{id}', [ExpertController::class, 'show'])->name('create-expert.update');
    Route::resource('new-expert', ExpertController::class);

    // faq
    Route::get('faqurl', [FaqUrlController::class, 'index'])->name('faqurl');
    route::post('/faqstore', [FaqUrlController::class, 'FaqnameStore'])->name('faqstore');
    Route::put('updatefaqUrl/{id}', [FaqUrlController::class, 'update'])->name('faq.update');
    Route::delete('/faqUrlDelete/{id}', [FaqUrlController::class, 'faqUrlDelete'])->name('faqUrlDelete.destroy');
    Route::post('/store-faqs', [FaqUrlController::class, 'storeFAQs'])->name('faqs.store');
    Route::get('newfaq', [NewFaqController::class, 'index'])->name('newfaq');
    Route::post('/update-account-status/{id}', [MasterController::class, 'updateAccountStatus'])->name('update.account.status');

    //  blog 
    Route::get('/write_blog', [HomeController::class, 'write_blog'])->name('write_blog');
    Route::get('/blog_list', [HomeController::class, 'blog_list'])->name('blog_list');
    Route::get('/blog_edit/{id}', [HomeController::class, 'blogedit'])->name('blog_edit');
    Route::put('/blog/{id}', [HomeController::class, 'blog_edit'])->name('blog.update');
    Route::post('/submit_blog', [HomeController::class, 'blog_store'])->name('submit_blog');
    Route::delete('/blogs/{id}', [HomeController::class, 'destroyBlog'])->name('blogs.destroy');
    Route::delete('/blog_list/{id}', [HomeController::class, 'editBlog'])->name('blogs.edit');

    // Sample
    Route::get('/create_sample', [HomeController::class, 'create_sample'])->name('create_sample');

    Route::post('/submit_sample', [HomeController::class, 'sample_store'])->name('submit_sample');
    Route::get('/sample/{slug}', [HomeController::class, 'getSampleBySlug']);
    Route::delete('/sample/{id}', [HomeController::class, 'destroySample'])->name('sample.destroy');
    Route::delete('/sample_list/{id}', [HomeController::class, 'editSample'])->name('sample.edit');
    route::get('/free-sample', [SampleController::class, 'index'])->name('free-sample');
    route::post('/free-sample', [SampleController::class, 'store'])->name('free-sample');
    Route::put('/sample-category/{id}', [SampleController::class, 'update'])->name('sample-category.update');
    Route::delete('/sample-category/{id}', [SampleController::class, 'destroy'])->name('sample-category.destroy');
    route::get('/free-sample-write', [SampleController::class, 'samplewrite'])->name('free-sample-write');
    route::post('/free-sample-write', [SampleController::class, 'storeService'])->name('services.store');
    Route::get('/samples', [SampleController::class, 'samplesShows'])->name('samples');
    Route::get('/update{id}', [SampleController::class, 'samplesUpades'])->name('free-samples.update');
    Route::put('/services/{id}', [SampleController::class, 'Sampleupdate'])->name('services.update');
    Route::delete('/deletesample/{id}', [SampleController::class, 'destroySample'])->name('deletesample');
    route::get('/free-samples-type', [SampleController::class, 'indexType'])->name('free-samples-type');
    route::post('/free-samples-type', [SampleController::class, 'sampleTypeStore'])->name('free-samples-type');
    Route::put('/sample-type/{id}', [SampleController::class, 'sampleTypeUpdate'])->name('sample-type.update');
    Route::delete('/sample-type/{id}', [SampleController::class, 'destroysampleType'])->name('sample-type.destroy');

    Route::delete('/sample/{id}', [SampleController::class, 'destroysample'])->name('sample.destroy');

    Route::get('/order-writer', [OrderController::class, 'orderWD'])->name('order.writer');
    Route::post('/order-writer', [OrderController::class, 'orderWD2'])->name('order.writer2');
    Route::POST('/update_status', [OrderController::class, 'updateStatus'])->name('update_status');
    Route::POST('/update_date', [OrderController::class, 'updateDate'])->name('update_date');
    route::get('/status-details', [OrderController::class, 'statusDetails'])->name('status-details');
    Route::get('/writer-available', [OrderController::class, 'writerAvailable'])->name('writer.available');

    Route::get('/order-feedback', [OrderController::class, 'orderFeedback'])->name('order.feedback');
    Route::post('/admin/order-feedback/mark-complete', [OrderController::class, 'markFeedback'])->name('orders.mark-feedback');
    Route::get('/dashboard/conversion-ratio-data', [HomeController::class, 'conversionRatioData'])
        ->name('dashboard.conversion.ratio.data');
    Route::get('/search-user', [SearchController::class, 'searchUser'])->name('search-user');

    // Lead management
    Route::prefix('lead')->group(function () {
        Route::get('/', [LeadsController::class, 'indexLead'])->name('lead.index');
        Route::get('/load-more', [LeadsController::class, 'loadMore'])->name('lead.loadMore');
        Route::get('/filter', [LeadsController::class, 'filter'])->name('lead.filter');
        Route::get('/edit/{id}', [LeadsController::class, 'editLead'])->name('lead.edit');
        Route::post('/update/{id}', [LeadsController::class, 'update'])->name('lead.update');
        Route::post('/convert/{id}', [LeadsController::class, 'convertLeadData'])->name('lead.convert');
        Route::post('/update-user/{id}', [LeadsController::class, 'updateUsernew'])->name('lead.update.user');
        Route::put('/cancel/{id}', [LeadsController::class, 'leads_Cancel'])->name('lead.cancel');
        Route::get('/get/{offset?}', [LeadsController::class, 'getLead'])->name('lead.get');
        Route::post('/whatsapp/{id}', [LeadsController::class, 'whatsapp'])->name('lead.whatsapp');
        Route::get('/fetch-templates/{userId}', [LeadsController::class, 'fetchTemplates'])->name('lead.fetchTemplates');
        Route::get('/chat/{leadId}', [LeadsController::class, 'LeadChat'])->name('lead.chat');
        Route::post('/chat/send', [LeadsController::class, 'callStore'])->name('chat.send');
        Route::put('/checklead/{id}', [LeadsController::class, 'checked'])->name('lead.checklead');
        Route::get('/leads-tracking-data', [LeadsController::class, 'getLeadTrackingData'])->name('leads.tracking.data');
    });

    // Order management
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'indexOrder'])->name('orders.index');
        Route::get('/edit/{orderId}', [OrderController::class, 'editOrder'])->name('orders.edit');
        Route::get('/filter', [OrderController::class, 'filter'])->name('orders.filter');
        Route::get('/comment-drawer/{id}', [OrderController::class, 'loadCommentDrawer'])->name('orders.comment.drawer');;
        Route::get('/payment/{orderId}/{paymentId?}', [OrderController::class, 'showPaymentForm'])->name('orders.payment.form');
        Route::post('/payment/store/{orderId}', [OrderController::class, 'storePayment'])->name('orders.payment.form.store');
        Route::put('/payment/update/{orderId}/{paymentId}', [OrderController::class, 'updatePayment'])->name('orders.payment.form.update');
        Route::get('/urgent-orders', [OrderController::class, 'urgentOrders']);
        Route::get('/statuses/list', [OrderController::class, 'getStatuses']);
        Route::post('/order-save-marks', [OrderController::class, 'saveMarks'])->name('orders.save.marks');
    });

    // History Management 
    Route::prefix('login-history')->group(function () {
        Route::get('/', [UserController::class, 'indexLog'])->name('admin.user.session');
        Route::post('/{user}/logout', [UserController::class, 'forceLogout'])->name('admin.users.logout');
        Route::get('/{user}/history', [UserController::class, 'userHistory'])->name('admin.users.history');
        Route::get('/filter/ajax', [UserController::class, 'filterLoginHistory'])->name('admin.user.session.filter');
    });

    Route::get('/fetch-team-members', function () {
        try {
            // Get the role_id from the request
            $roleId = request()->get('role_id');

            // Fetch team members based on the role_id
            $teamMembers = User::where('role_id', $roleId)->get();

            // Return team members as JSON response
            return response()->json(['teamMembers' => $teamMembers]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error fetching team members: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    });

    Route::get('/prime-leads', [PrimeassimentController::class, 'showPrimeLeads']);
    Route::get('/smart-leads', [PrimeassimentController::class, 'showSmartLeads']);
    Route::post('/store-lead', [PrimeassimentController::class, 'store']);
    Route::put('/update-lead/{id}', [PrimeassimentController::class, 'update']);
    Route::post('/convert-lead/{id}', [PrimeassimentController::class, 'convertToOrder']);
    Route::post('/cancel-lead/{id}', [PrimeassimentController::class, 'cancelLead']);
    Route::get('/prime-orders', [PrimeassimentController::class, 'showPrimeOrders']);
    Route::get('/prime-cancelled', [PrimeassimentController::class, 'showPrimeCancelled']);
    Route::get('/smart-orders', [PrimeassimentController::class, 'showSmartOrders']);
    Route::get('/smart-cancelled', [PrimeassimentController::class, 'showSmartCancelled']);
    Route::post('/restore-lead/{id}', [PrimeassimentController::class, 'restoreLead']);
    Route::delete('/delete-lead/{id}', [PrimeassimentController::class, 'destroy']);
    Route::get('/feedback-list', [OrderController::class, 'feedbackList'])->name('feedback.list');
    Route::post('/feedback-update/{id}', [OrderController::class, 'feedbackUpdate'])->name('feedback.update');
    Route::get('/feedback-delete/{id}', [OrderController::class, 'feedbackDelete'])->name('feedback.delete');
    Route::post('/update-working-time', [UserController::class, 'updateTime'])->name('user.update-time');
    Route::get('/admin/working-report', [UserController::class, 'adminWorkingReport'])->name('admin.working-report');
    Route::get('/admin/user-history/{userId}', [UserController::class, 'userHistory'])->name('user.history');
    Route::post('/admin/force-logout/{user}', [UserController::class, 'forceLogout'])->name('admin.force-logout');
    Route::get('/admin/ticket-report', [OrderController::class, 'ticketReport'])->name('admin.ticket-report');
    Route::post('/user-save-review', [UserController::class, 'saveReview'])->name('user.save.review');
    // Route::post('/user-save-marks', [UserController::class, 'saveMarks'])->name('user.save.marks');
    Route::post('/order-rate-writer', [OrderController::class, 'rateWriter'])->name('order.rate.writer');
    Route::get('/user/report/{id}', [UserController::class, 'showUserProfile'])->name('user.report');
    Route::get('/user/report-list', [UserController::class, 'userReportList'])->name('user.report.list'); //reporting list
    Route::get('/user-report', [UserHistoryController::class, 'report'])->name('user.user-report');
    Route::get('/user-report/list', [UserHistoryController::class, 'getList'])->name('user.user-report');
    Route::get('/user/report-list/data', [UserHistoryController::class, 'getReportUsers'])->name('user.report.users'); //reporting list


    });
    // Route::get('/analytics-report', [UserHistoryController::class, 'analyticsReport'])->name('analytics.report');
    Route::get('/analytics-report/{type}', [UserHistoryController::class, 'analyticsReport'])
    ->name('analytics.report');

    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('authors.index');
        Route::post('/store', [AuthorController::class, 'store'])->name('authors.store');
        Route::post('/update/{id}', [AuthorController::class, 'update'])->name('authors.update');
        Route::delete('/delete/{id}', [AuthorController::class, 'destroy'])->name('authors.delete');
    });

    Route::post('/orders/change-team', [OrderController::class, 'changeTeam'])
    ->name('orders.change.team');
    

    route::middleware(['auth'])->group(function () {
        Route::get('/group-master', [GroupMasterController::class, 'index'])->name('group.master.index');
        Route::post('/group-master/store', [GroupMasterController::class, 'store'])->name('group.master.store');
        Route::post('/group-master/update/{id}', [GroupMasterController::class, 'update'])->name('group.master.update');
        Route::delete('/group-master/delete/{id}', [GroupMasterController::class, 'destroy'])->name('group.master.delete');
        Route::get('/search-refer-users', [LeadsController::class, 'searchReferUsers'])->name('search.refer.users');
        Route::get('/refer-user-report', [UserController::class, 'referUserReport'])->name('refer.user.report');
        Route::get('/module-code-report', [OrderController::class, 'moduleCodeReport'])->name('module.code.report');
        Route::get('/search-module-codes', [OrderController::class, 'searchModuleCodes'])->name('search.module.codes');
        Route::get('/university-report', [OrderController::class, 'universityReport'])->name('university.report');
        Route::get('/search-university', [OrderController::class, 'searchUniversity'])->name('search.university');
        Route::get('/follow-up-report', [OrderController::class, 'followUpReport'])->name('follow.up.report');
        Route::post('/duplicate-lead', [LeadsController::class, 'duplicateLead'])->name('lead.duplicate');
        Route::get('/duplicate-leads', [LeadsController::class, 'duplicateLeads'])->name('duplicate.leads');
        Route::get('/paper-type-report', [UserHistoryController::class, 'paperTypeReport'])->name('paper.type.report');
        Route::get('/paper-type-report/orders', [UserHistoryController::class, 'paperTypeOrders'])->name('paper.type.report.orders');
        Route::post('/orders/looking-refund', [OrderController::class, 'markLookingRefund'])->name('orders.looking.refund');
        Route::get('/refund-orders', [OrderController::class, 'refundOrders'])->name('orders.refund.list');
        Route::post('/orders/additional-save', [OrderController::class, 'saveAdditional'])->name('orders.additional.save');
        Route::get('/orders/additional-history/{orderId}', [OrderController::class, 'additionalHistory'])->name('orders.additional.history');
        Route::post('/user/break/start', [HomeController::class, 'startBreak'])->name('user.break.start');
        Route::post('/user/break/end', [HomeController::class, 'endBreak'])->name('user.break.end');
        Route::get('/user/break/current', [HomeController::class, 'currentBreak'])->name('user.break.current');
        Route::get('/break-time-report', [HomeController::class, 'breakTimeReport'])->name('break.time.report');
        Route::get('/user-retention-report', [UserHistoryController::class, 'userRetentionReport'])->name('user.retention.report');
    });

    
