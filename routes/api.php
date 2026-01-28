<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/users", [UserController::class, "index"]);


    });
    
        
        Route::get("/category",[CategoryController::class,"index"]);
        Route::post("/category",[CategoryController::class,"store"]);
        Route::delete("/category/{id}",[CategoryController::class,"destroy"]);
        Route::patch("/category/{id}",[CategoryController::class,"update"]);
    
        //product
        Route::get("/product",[ProductController::class,"index"]);
        Route::post("/product",[ProductController::class,"store"]);
        Route::get('product/{id}', [ProductController::class, 'show']); 
        Route::delete("/product/{id}",[ProductController::class,"destroy"]);
        Route::patch("/product/{id}",[ProductController::class,"update"]);
    
        //supplier
        Route::get("/supplier",[SupplierController::class,"index"]);
        Route::post("/supplier",[SupplierController::class,"store"]);
        Route::delete("/supplier/{id}",[SupplierController::class,"destroy"]);
        Route::patch("/supplier/{id}",[SupplierController::class,"update"]);
    
            //order
        Route::get("/order",[OrderController::class,"index"]);
        Route::post("/order",[OrderController::class,"store"]);
        Route::delete("/order/{id}",[OrderController::class,"destroy"]);
        Route::patch("/order/{id}",[OrderController::class,"update"]);
    
        //orderitem
        Route::get("/orderitem",[OrderItemController::class,"index"]);
        Route::post("/orderitem",[OrderItemController::class,"store"]);
        Route::delete("/orderitem/{id}",[OrderItemController::class,"destroy"]);
        Route::patch("/orderitem/{id}",[OrderItemController::class,"update"]);
    
        //stockmovement
        Route::get("/stock",[OrderItemController::class,"index"]);
        Route::post("/stock",[OrderItemController::class,"store"]);
        Route::delete("/stock/{id}",[OrderItemController::class,"destroy"]);
        Route::patch("/stock/{id}",[OrderItemController::class,"update"]);
    //category
    


