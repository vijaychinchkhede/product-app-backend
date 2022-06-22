<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Cart;

class OrderController extends Controller
{
    public function createOrder(Request $request){
        try{
                $now = Carbon::now();
                $order = new Order;
                $order->user_id = $request->user_id;
                $order->product_ids = implode(',', $request->product_ids);
                $order->products = implode(',', $request->products);
                $order->amount_paid = $request->amount_paid;
                $order->order_at = $now;
                $order->save();

                $deleteCartProduct = Cart::where('user_id',$request->user_id)->delete();


                $content =[
                    'status' =>200,
                    'message' =>'Order created successsfuly',
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
    public function getAllOrderOfUser(Request $request){
        try{
            $order_data = Order::where('user_id',$request->user_id)->get();
             if($order_data->isNotEmpty()){
                    $content =[
                    'status' =>200,
                    'message' =>'Data found successsfuly',
                    'data' => $order_data,
                ];
                }else{
                    $content =[
                    'status' =>201,
                    'message' =>'No data successsfuly',
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
}
