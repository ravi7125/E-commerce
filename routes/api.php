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
use App\Http\Middleware\CheckRole;
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

// Auth route:
Route::controller(AuthController::class)->prefix('auth')->group(function () { 
    Route::post ("create", 'create'); 
    Route::post  ('login', 'login');
    Route::post  ('/forgotPasswordLink','forgotPasswordLink');
    Route::post  ('/forgotPassword', 'forgotPassword');
    Route::post  ("list", 'list');    
});

// User route:
 Route::group(['middleware' => ['auth:sanctum']], function () {
Route::controller(UserController::class)->prefix('user')->group(function () { 
    Route::get    ("view/{id?}", 'view')    ->middleware ('role:admin|user');
    Route::delete ("delete/{id}", 'destroy')->middleware ('role:admin|user');
    Route::put    ("update/{id}", 'update') ->middleware ('role:admin|user');
    Route::post   ('/logout', 'logout')     ->middleware ('role:admin|user');
    Route::post ('/changepassword','changepassword')->middleware ('role:admin|user');
    Route::post   ("list", 'list')          ->middleware ('role:admin|user');  
});
// Category route:
Route::controller(CategoryController::class)->prefix('category')->group(function(){
    Route::post  ("create", 'create')      ->middleware ('role:admin');
    Route::get   ("view/{id?}", 'view')    ->middleware ('role:admin|user');
    Route::delete("delete/{id}",'destroy') ->middleware ('role:admin');
    Route::put   ("/update/{id}", 'update')->middleware ('role:admin');
    Route::post  ("list",'list')           ->middleware ('role:admin|user'); 
});
// Subcategory route:
Route::controller(SubcategoryController::class)->prefix('subcategory')->group(function(){
    Route::post  ("create", 'create')      ->middleware ('role:admin'); 
    Route::get   ("view/{id?}", 'view')    ->middleware ('role:admin|user');
    Route::delete("delete/{id}", 'destroy')->middleware ('role:admin');
    Route::put   ("update/{id}", 'update') ->middleware ('role:admin');
    Route::post   ("list", 'list')         ->middleware ('role:admin|user'); 
    });
// Products route:    
Route::controller(ProductsController::class)->prefix('products')->group(function(){
    Route::post  ("create", 'create')       ->middleware ('role:admin'); 
    Route::get   ("view/{id?}", 'view')     ->middleware ('role:admin|user'); 
    Route::delete("delete/{id}", 'destroy') ->middleware ('role:admin'); 
    Route::put   ("update/{id}", 'update')  ->middleware ('role:admin'); 
    Route::post  ("list", 'list')           ->middleware ('role:admin|user');
});
// Cart route:       
Route::controller(CartController::class)->prefix('cart')->group(function(){
    Route::post  ("create", 'create')        ->middleware ('role:admin'); 
    Route::get   ("view/{id?}", 'view')      ->middleware ('role:admin|user'); 
    Route::delete("delete/{id}", 'destroy')  ->middleware ('role:admin'); 
    Route::put  ("update/{id}", 'update')   ->middleware ('role:admin'); 
    Route::post  ("list", 'list')            ->middleware ('role:admin|user');   
});
// Order route:  
Route::controller(OrderController::class)->prefix('order')->group(function(){
    Route::post  ("create", 'create')        ->middleware ('role:user'); 
    Route::get   ("view/{id?}", 'view')      ->middleware ('role:admin|user');
    Route::delete("delete/{id}", 'destroy')  ->middleware ('role:admin');
    Route::put   ("update/{id}", 'update')   ->middleware ('role:admin');
    Route::post  ("list", 'list')            ->middleware ('role:admin|user'); 
});
    
});






