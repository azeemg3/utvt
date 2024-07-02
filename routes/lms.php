<?php

use App\Http\Controllers\Lms\LeadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//require __DIR__.'/auth.php';

Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::prefix('lms')->group(function(){
        Route::resource('lead',LeadController::class);
        Route::get('all_leads',[LeadController::class,'all_leads'])->name('lead.all_leads');
        Route::get('my_leads',[LeadController::class,'my_leads'])->name('lead.my_leads');
        Route::get('lead_activity/{LID}',[LeadController::class,'lead_activity'])->name('lead.activiy');
        Route::get('check-lead/{id}',[LeadController::class,'check_lead']);
        Route::post('lead-takeover',[LeadController::class,'takeover_lead'])->name('lead.takeover');
        Route::post('lead-conversation',[LeadController::class,'lead_conversation'])->name('lead.conversation');
        Route::get('lead-conversation/{id}',[LeadController::class,'lead_conversation']);
        Route::get('change-status/{id}/{status}',[LeadController::class,'change_status']);
        Route::post("lead-reason",[LeadController::class,'lead_reason'])->name('lead.lead_reason');
        Route::get('reopen/{id}',[LeadController::class,'reopen_lead']);
        Route::put('lead_reopen',[LeadController::class,'lead_reopen']);
        Route::post('transfer_lead',[LeadController::class,'transfer_lead'])->name('lead.lead_transfer');
        Route::get("lead-reminder/{type?}/{leadId?}",[LeadController::class,'lead_reminder'])->name('lead.lead_reminder');
        Route::get("reminder-read",[LeadController::class,'reminder_read'])->name('lead.reminder_read');
        Route::get("edit-reminder",[LeadController::class,'edit_reminder'])->name('lead.edit_reminder');
        Route::post("save-reminder",[LeadController::class,'save_reminder'])->name('lead.save_reminder');
        Route::get('reopen_leads',[LeadController::class,'reopen_leads'])->name('leads.reopen_leads');
    });
});


