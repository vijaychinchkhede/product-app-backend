<?php

use Illuminate\Support\Facades\Route;
   
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

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

// Route::get('/', function () {
//     return view('welcome');
// });



Route::post('api/login', [UserController::class, 'login']);
Route::post('api/register', [UserController::class, 'register']);


Route::post('api/getallproduct', [ProductController::class, 'getAllProduct']);
Route::post('api/addproduct', [ProductController::class, 'addProduct']);
Route::post('api/getusercartitems', [ProductController::class, 'getUserCartItems']);
Route::post('api/addtocart', [ProductController::class, 'addToCart']);
Route::post('api/getproductdetailsbyid', [ProductController::class, 'getProductDetailsById']);
Route::post('api/updateproductdetails', [ProductController::class, 'updateProductDetails']);
Route::post('api/deleteproduct', [ProductController::class, 'deleteProduct']);


Route::post('api/createorder', [OrderController::class, 'createOrder']);
Route::post('api/getallorderofuser', [OrderController::class, 'getAllOrderOfUser']);


 