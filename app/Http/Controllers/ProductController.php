<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Cart;

class ProductController extends Controller
{
   
 public function getAllProduct(Request $request){
        try{
            $query = Product::where('status','active');
            if(!empty($request->name)){
                $query->where('name','LIKE',"%{$request->name}%");
            }
            $product_data = $query->get();
                if($product_data->isNotEmpty()){
                    $content =[
                    'status' =>200,
                    'message' =>'data found successsfuly',
                    'data' => $product_data,
                ];
                }
            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }
        return response()->json($content);
    }

    public function addProduct(Request $request){
        try{
                $product = new Product;
                $product->name = $request->name;
                $product->price = $request->price;
                $product->description = $request->description;
                $product->save();

                $content =[
                    'status' =>200,
                    'message' =>'Product updated successsfuly',
                    'data' => '',
                ];

            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }
        return response()->json($content);
    }

    public function getUserCartItems(Request $request){
        try{
            $user_id = $request->user_id;
            $query = Cart::join('products', function ($join) {
                        $join->on('products.id', 'cart.product_id');
                    });
            $query->where('user_id',$user_id);

            if(!empty($request->name)){
                $query->where('name','LIKE',"%{$request->name}%");
            }

            $cart_data = $query->get();
                if($cart_data->isNotEmpty()){
                    $content =[
                    'status' =>200,
                    'message' =>'Data found successsfuly',
                    'data' => $cart_data,
                  ];
                }else{
                   $content =[
                    'status' =>201,
                    'message' =>'Data not found ',
                    'data' => '',
                ]; 
                }
            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }

        return response()->json($content);
    }
    public function addToCart(Request $request){
        try{
            $checkproduct = Cart::where('product_id',$request->product_id)->where('user_id',$request->user_id)->first();
            if(empty($checkproduct)){
                $additems = new Cart;
                $additems->user_id = $request->user_id;
                $additems->product_id = $request->product_id;
                $additems->product_name = $request->product_name;
                $additems->quantity = 1;
                $additems->save();
            }else{
                $updateItem = Cart::find($checkproduct->id);
                $updateItem->quantity = $checkproduct->quantity + 1;
                $updateItem->save();
            }

                $content =[
                    'status' =>200,
                    'message' =>'Item added to cart successsfuly',
                    'data' => '',
                ];

            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }
        return response()->json($content);
    }
    public function getProductDetailsById(Request $request){
        try{
            $product_id = $request->product_id;
            $product_data = Product::where('id',$product_id)->first();
                if(!empty($product_data)){
                    $content =[
                    'status' =>200,
                    'message' =>'Data found successsfuly',
                    'data' => $product_data,
                ];
                }else{
                   $content =[
                    'status' =>201,
                    'message' =>'Data not found',
                    'data' => '',
                ]; 
                }
            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }

        return response()->json($content);
    }
    public function updateProductDetails(Request $request){
        try{
                $product_id = $request->product_id;
                $updateProduct = Product::find($product_id);
                $updateProduct->name = $request->name;
                $updateProduct->price = $request->price;
                $updateProduct->description = $request->description;
                $updateProduct->save();

                $content =[
                    'status' =>200,
                    'message' =>'Product updated successsfuly',
                    'data' => '',
                ];

            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }
        return response()->json($content);
    }
    public function deleteProduct(Request $request){
        try{
                $product_id = $request->product_id;
                $deleteProduct = Product::find($product_id);
                $deleteProduct->status = 'inactive';
                $deleteProduct->save(); 

                $content =[
                    'status' =>200,
                    'message' =>'Product has been deleted successsfuly',
                    'data' => '',
                ];

            }catch (exception $e) {
                $content =[
                    'status' =>500,
                    'message' =>$e
                ];
            }
        return response()->json($content);
    }
}
