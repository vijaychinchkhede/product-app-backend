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
Route::post('api/getalluser', [UserController::class, 'getAllUser']);
Route::post('api/deleteuser', [UserController::class, 'deleteUser']);



Route::post('api/getallproduct', [ProductController::class, 'getAllProduct']);
Route::post('api/addproduct', [ProductController::class, 'addProduct']);
Route::post('api/getusercartitems', [ProductController::class, 'getUserCartItems']);
Route::post('api/addtocart', [ProductController::class, 'addToCart']);
Route::post('api/getproductdetailsbyid', [ProductController::class, 'getProductDetailsById']);
Route::post('api/updateproductdetails', [ProductController::class, 'updateProductDetails']);
Route::post('api/deleteproduct', [ProductController::class, 'deleteProduct']);
Route::post('api/getallactiveproduct', [ProductController::class, 'getAllActiveProduct']);
Route::post('api/removeproductfromcart', [ProductController::class, 'removeProductFromCart']);
Route::put('api/updatecartproductquantity', [ProductController::class, 'updateCartProductQuantity']);





Route::post('api/createorder', [OrderController::class, 'createOrder']);
Route::post('api/getallorderofuser', [OrderController::class, 'getAllOrderOfUser']);
Route::post('api/getalluserorder', [OrderController::class, 'getAllUserOrder']);
Route::put('api/updateorderstatus', [OrderController::class, 'updateOrderStatus']);




 