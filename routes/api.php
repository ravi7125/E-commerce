<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//auth
Route::controller(AuthController::class)->prefix('auth')->group(function () { 
    Route::post("create", 'create'); 
    Route::post('login', 'login');
    Route::post('/forgotPasswordLink', 'forgotPasswordLink');
    Route::post('/forgotPassword', 'forgotPassword');
    Route::post("list", 'list');

    
});

//User route
Route::group(['middleware' => ['auth:sanctum']], function () {
Route::controller(UserController::class)->prefix('user')->group(function () { 
    Route::get("view/{id?}", 'view');
    Route::delete("delete/{id}", 'destroy');
    Route::put("update/{id}", 'update');
    Route::post('/logout', 'logout');
    Route::post('/changepassword', 'changepassword');
    Route::post("list", 'list'); 
    
    });
});

Route::controller(CategoryController::class)->prefix('category')->group(function(){
    Route::post("create", 'create'); 
    Route::get("view/{id?}", 'view');
    Route::delete("delete/{id}", 'destroy');
    Route::put("/update/{id}", 'update');
    Route::post("list", 'list'); 
});

Route::controller(SubcategoryController::class)->prefix('subcategory')->group(function(){
    Route::post("create", 'create'); 
    Route::get("view/{id?}", 'view');
    Route::delete("delete/{id}", 'destroy');
    Route::put("update/{id}", 'update');
    Route::post("list", 'list'); 
});

Route::controller(ProductsController::class)->prefix('products')->group(function(){
    Route::post("create", 'create'); 
    Route::get("view/{id?}", 'view');
    Route::delete("delete/{id}", 'destroy');
    Route::put("update/{id}", 'update');
    Route::post("list", 'list'); 
});

Route::controller(CartController::class)->prefix('cart')->group(function(){
    Route::post("create", 'create'); 
    Route::get("view/{id?}", 'view');
    Route::delete("delete/{id}", 'destroy');
    Route::put("update/{id}", 'update');
    Route::post("list", 'list'); 
});
Route::controller(OrderController::class)->prefix('order')->group(function(){
    Route::post("create", 'create'); 
    Route::get("view/{id?}", 'view');
    Route::delete("delete/{id}", 'destroy');
    Route::put("update/{id}", 'update');
    Route::post("list", 'list'); 
});