<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificaitonController;
use App\Models\User;
use App\Notifications\PushNotification;

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
Route::get('test', function () {
    event(new App\Events\NotificationStatus('notification-send'));
    return "Event has been sent!";
});

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', function () {
        return view('index');
    })->name('dashboard');
    Route::get('/', function () {
        return view('index');
    })->name('dashboard');
    Route::get('logout', function () {
        Session::flush();
        Auth::logout();
        return redirect('login');
    });
    Route::get('home', function () {
        return view('home');
    });
    Route::post('store_token', [UserController::class, 'store_token']);
    Route::post('/send-web-notification', [UserController::class, 'sendNotification'])->name('send.web-notification');
    Route::get('/send-whatsapp', [UserController::class, 'sendWhatsAppMessage']);
    Route::resource('fetch-notification',NotificaitonController::class);
    Route::get('queue_run',function(){
        Artisan::call('queue:listen');
        dd('done');
    });
    Route::get('all-notifications',[NotificaitonController::class,'all_notifications'])->name('all-notifications');
});
