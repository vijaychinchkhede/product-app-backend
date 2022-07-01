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
                    'message' =>'No data found',
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
    public function getAllUserOrder(Request $request){
        try{
            $query = Order::join('users', function ($join) {
                                $join->on('users.id', 'user_orders.user_id');
                            });

            if(!empty($request->name)){
               $query->where('name','LIKE',"%{$request->name}%")->get();
            }
            $query->select('user_orders.id as order_id','user_orders.products','user_orders.amount_paid','user_orders.status','users.name','users.id as user_id');
            $total = $query->count();
            $query->simplePaginate(10);
            $objOrderData = $query->get();

            if($objOrderData->isNotEmpty()){
                $content =[
                    'status' =>200,
                    'message' =>'Data found successsfuly',
                    'data' => $objOrderData,
                    'count' => $total,
                ];
            }else{
                $content =[
                    'status' =>201,
                    'message' =>'No data found',
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
    public function updateOrderStatus(Request $request){
        try{
            $now = Carbon::now();
            $intOrederID = $request->order_id;
            $updateOrder = Order::find($intOrederID);
            $updateOrder->status = 'order-cancelled';
            $updateOrder->updated_at = $now;
            $updateOrder->save();
            
                $content =[
                    'status' =>200,
                    'message' =>'Order status updated successsfuly',
                    'data' => '',
                ];
           
        }catch (exception $e) {
            $content =[
                'status' =>500,
                'message' =>$e->message
            ];
        }
        return response()->json($content);
    }
}
