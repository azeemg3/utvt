<?php

use App\Http\Controllers\Settings\AirlineController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\SectorController;
use App\Http\Controllers\Settings\ServiceController;
use App\Http\Controllers\Settings\SourceController;
use App\Http\Controllers\Settings\UserController;
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
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('settings')->group(function () {
        Route::prefix('user-management')->group(function () {
            Route::resource('user', UserController::class);
            Route::resource('role', RoleController::class);
            Route::resource('permission', PermissionController::class);
            Route::post('save-user',[UserController::class,'save_user'])->name('user.save');
        });
        Route::resource('service', ServiceController::class);
        Route::resource('source', SourceController::class);
        Route::resource('business', \App\Http\Controllers\Settings\BusinessSettingController::class);
        Route::resource('country', \App\Http\Controllers\Settings\CountryController::class);
        Route::resource('city', \App\Http\Controllers\Settings\CityController::class);
        Route::resource('sector', SectorController::class);
        Route::resource('airline',AirlineController::class);
    });
});
