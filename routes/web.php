<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccessRightController;
use App\Http\Controllers\CompanyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/', function(){
        return redirect('/login');
    });
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/product-category', [ProductCategoryController::class, 'index']);

    // User
    Route::get('/user', [UserController::class, 'index']);
    // Access Right
    Route::get('/access-right', [AccessRightController::class, 'index']);
    // Company
    Route::get('/company', [CompanyController::class, 'index']);

});

Route::middleware(['adminapi'])->group(function () {
    // User
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/user/data', [UserController::class, 'data']);
    Route::get('/user/add', [UserController::class, 'add']);
    Route::post('/user/add', [UserController::class, 'doAdd']);
    Route::get('/user/edit/{id}', [UserController::class, 'edit']);
    Route::post('/user/edit', [UserController::class, 'doEdit']);
    Route::get('/user/detail/{id}', [UserController::class, 'detail']);
    Route::get('/user/delete/{id}', [UserController::class, 'delete']);

    // Access Right
    Route::post('/access-right/data', [AccessRightController::class, 'data']);
    Route::get('/access-right/add', [AccessRightController::class, 'add']);
    Route::post('/access-right/add', [AccessRightController::class, 'doAdd']);
    Route::get('/access-right/edit/{id}', [AccessRightController::class, 'edit']);
    Route::post('/access-right/edit', [AccessRightController::class, 'doEdit']);
    Route::get('/access-right/detail/{id}', [AccessRightController::class, 'detail']);
    Route::get('/access-right/delete/{id}', [AccessRightController::class, 'delete']);

    // Company
    Route::post('/company', [CompanyController::class, 'doEdit']);
});
