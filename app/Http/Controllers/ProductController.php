<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Cart;


class ProductController extends Controller
{

   public function getAllProduct(Request $request){
    try{
        if(!empty($request->all())){
            if(!empty($request->name) && empty($request->category)){
              $objProductData = Product::where('name','LIKE',"%{$request->name}%")->orderBy('id', 'desc')->get();   
            }else if(empty($request->name) && !empty($request->category)){
              $objProductData = Product::where('category_id',$request->category)->orderBy('id', 'desc')->get();   
            }else if(!empty($request->name) && !empty($request->category)){
                $objProductData = Product::where('name','LIKE',"%{$request->name}%")->where('category_id',$request->category)->orderBy('id', 'desc')->get();
            }else{
               $objProductData = Product::orderBy('id', 'desc')->get(); 
            }
         }else{
            $objProductData = Product::orderBy('id', 'desc')->get();
        }

    if($objProductData->isNotEmpty()){
        $content =[
            'status' =>200,
            'message' =>'Data found successsfuly',
            'data' => $objProductData,
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

public function addProduct(Request $request){
    try{
        $now = Carbon::now();
        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->product_add_at = $now;
        $product->category_id = $request->category;
        $product->subcategory_id = 0;
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
        $query->select('cart.id as cart_id','cart.product_name','cart.quantity','products.id as product_id','products.description','products.price','products.stripe_price_code');
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
        $now = Carbon::now();
        $product_id = $request->product_id;
        $updateProduct = Product::find($product_id);
        $updateProduct->name = $request->name;
        $updateProduct->price = $request->price;
        $updateProduct->description = $request->description;
        $updateProduct->status = $request->status;
        $updateProduct->product_updated_at = $now;
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

public function getAllActiveProduct(Request $request){
    try{
        $query = Product::where('status','active')->orderBy('id', 'desc');
        if(!empty($request->name)){
            $query->where('name','LIKE',"%{$request->name}%");
        }
        if(!empty($request->category)){
            $query->where('category_id',$request->category);
        }

        $objProductData = $query->get();
        if($objProductData->isNotEmpty()){
            $content =[
                'status' =>200,
                'message' =>'Data found successsfuly',
                'data' => $objProductData,
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
    public function removeProductFromCart(Request $request){
        try{
            $intProductId = $request->product_id;
            $intUserId = $request->user_id;
            $deleteProduct = Cart::where('product_id',$intProductId)->where('user_id',$intUserId)->delete();

            $content =[
                'status' =>200,
                'message' =>'Product has been removed successsfuly',
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

    public function updateCartProductQuantity(Request $request){
        try{
            $now = Carbon::now();
            $intCartID = $request->cart_id;
            $updateCart = Cart::find($intCartID);
            if($request->updateStatus == 1){
                $updateCart->quantity  = $updateCart->quantity + 1; 
            }else{
                $updateCart->quantity  = $updateCart->quantity - 1;
            }
            $updateCart->updated_at  = $now;
            $updateCart->save();

            $content =[
                'status' =>200,
                'message' =>'Product quantity has been updated successsfuly',
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

    public function updateProductStatus(Request $request){
        try{
            $rules = [
            'product_id' => 'required',
            'status' => 'required',
            ];

            $messages = [
                 'name.required' => 'Product id is required.',
                 'status.required' => 'Status is required.',
            ];
            $validator = Validator::make( $request->all(), $rules, $messages );

            if ( $validator->fails() ) 
            {
                return [
                    'status' => 201, 
                    'message' => $validator->errors(),
                ];
            }

            $now = Carbon::now();
            $objUpdateProductStatus = Product::find($request->product_id);
            $objUpdateProductStatus->status = $request->status;
            $objUpdateProductStatus->product_updated_at = $now;
            $objUpdateProductStatus->save();

            $content =[
                'status' =>200,
                'message' =>'Product status has been updated successsfuly',
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
