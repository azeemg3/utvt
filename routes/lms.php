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
    });
});


