<?php

use App\Http\Controllers\BookmarkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSystem\AuthController;
use App\Http\Controllers\LoginSystem\LoginController;
use App\Http\Controllers\LoginSystem\PasswordController;
use App\Http\Controllers\LoginSystem\AuthMobileController;
use App\Http\Controllers\LoginSystem\VerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaceController;
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


Route::group(['middleware' => ['guest']], function () {
    //mobile
    Route::post('registerMobile', [AuthMobileController::class, 'registerMobile']);
    Route::post('regiseterMobileAdmin', [AuthMobileController::class, 'regiseterMobileAdmin']);
    Route::get('verify-email-mobile', [VerificationController::class, 'verifyOtp']);
    //mobile end
    //web
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('registerAdmin', [AuthController::class, 'registerAdmin']);
    Route::get('/login/google', [LoginController::class, 'redirectToGoogle']);
    Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    
    //Profile CRUD
    Route::post('/edit-profile/{id}', [ProfileController::class, 'UpdateProfile']);
    Route::post('/delete-profile/{id}', [ProfileController::class, 'DeleteProfile']);
    Route::post('/read-profile/{username}', [ProfileController::class, 'ReadProfile']);

    
    Route::post('/create-place', [PlaceController::class, 'CreatePlace']);
    Route::post('/update-place/{id}', [PlaceController::class, 'UpdatePlace']);
    Route::post('/delete-place/{id}', [PlaceController::class, 'DeletePlace']);
    Route::post('/read-place', [PlaceController::class, 'ReadAllPlace']);
    Route::post('/read-detail-place/{userame}/{id}', [PlaceController::class, 'ReadDetailPlace']);
    Route::post('/search', [PlaceController::class, 'search']);

    Route::post('/create-bookmark/{username}/{id}', [BookmarkController::class, 'CreateBookmark']);

    //web end
    Route::post('sendResetLink', [PasswordController::class, 'sendResetLink']);
    Route::post('resetPassword', [PasswordController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:api', 'role:user,admin']], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('changePassword/{id}', [PasswordController::class, 'changePassword']);
});
