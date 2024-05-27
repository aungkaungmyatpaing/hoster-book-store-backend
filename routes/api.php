<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DioceseController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\LibraryController;
use App\Http\Controllers\API\MonthController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\UserNotificationController;
use App\Http\Controllers\API\YearController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [AuthController::class, 'getUserProfile']);
    Route::post('/user/logout', [AuthController::class, 'logout']);
    Route::post('/user/update', [AuthController::class, 'updateProfile']);

    Route::get('/years', [YearController::class, 'getYears']);
    Route::get('/months', [MonthController::class, 'getMonths']);
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::get('/homePageCategories', [CategoryController::class, 'homePageCategories']);

    Route::get('/articles', [ArticleController::class, 'getArticles']);

    Route::get('/article/detail', [ArticleController::class, 'articleDetail']);

    Route::get('/payment-accounts', [PaymentController::class, 'getPaymentAccounts']);
    Route::get('/payments', [PaymentController::class, 'getPayments']);

    Route::get('/libraries', [LibraryController::class, 'getLibraries']);

    Route::post('/favorite/create', [FavoriteController::class, 'createFav']);
    Route::get('/favorite', [FavoriteController::class, 'getFav']);
    Route::delete('/favorite/delete', [FavoriteController::class, 'deleteFav']);

    Route::post('/subscriptions/create', [SubscriptionController::class, 'createSubscription']);


    Route::get('/notifications', [UserNotificationController::class, 'getNoti']);
    Route::post('/notifications/update', [UserNotificationController::class, 'updateNoti']);
    Route::delete('/notifications/delete', [UserNotificationController::class, 'deleteNoti']);

});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/dioceses', [DioceseController::class, 'getDioceses']);
