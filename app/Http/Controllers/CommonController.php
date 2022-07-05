<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CommonController extends Controller
{
    public function getDashboardCardData(Request $request){
        try{
            $data = [];
            $user_data = User::where('type','user')->groupBy('status')
                ->select('status', DB::raw('count(*) as total'))
                ->get();
            foreach($user_data as $val){
                if($val->status == 'active'){
                   $data['activeUser'] = $val->total;
                }else{
                    $data['inactiveUser'] = $val->total;
                }
            }

            $order_data = Order::groupBy('status')
                ->select('status', DB::raw('count(*) as total'))
                ->get();
            foreach($order_data as $val){
                if($val->status == 'order'){
                   $data['placedOrder'] = $val->total;
                }else{
                    $data['canceledOrder'] = $val->total;
                }
            }

            $data['mostLikeProduct'] = Product::select('id','name','product_cart_count')->orderBy('product_cart_count', 'desc')->first();

             $order = Order::groupBy('user_id')->select('user_id', DB::raw('count(*) as total'))->orderBy('total', 'desc')->where('status','order')->first();

             $userData = User::where('id',$order['user_id'])->first();
             $data['userData'] = $userData;

            $content =[
                'status' =>200,
                'message' =>'Data found successfuly',
                'data' => $data,
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
