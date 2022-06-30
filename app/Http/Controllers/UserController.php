<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
   public function register (Request $request){
        $rules = array(
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile_number' => 'required|digits:10|unique:users',
            'password' => 'required',
            
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response()->json(['status' => 'failure', 'message' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        $now = Carbon::now();
        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => $request->password,
            'encrypted_password' =>Hash::make($request->password),
            'created_at' => $now,
            'status' => 'active',
        ]);
        return Response()->json(['status' => '200', 'message' => 'User has been register successsfuly..!']);
    }

    public function login (Request $request){
        $rules = array(
            'username' => 'required|email',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response()->json(['status' => 'failure', 'message' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        $email = $request->username;
        $password = $request->password;
        $login = User::where('email',$email)->where('password',$password)->where('status','active')->first();
        if (!empty($login)) {
            $request->session()->put(array('UserId'=>$login->id,'name'=>$login->name));
                $userDetails = array(
                    'user_id' =>$login->id,
                    'name' => $login->name,
                    'email' => $login->email,
                    'userType' => $login->type,
                );
                $randomString = Str::random(16);
                $userToken = User::where('id',$login->id)->update(['token'=>$randomString]);
            return Response()->json(['status' => 'success', 'message' => 'Successful..!', 'userDetails' => $userDetails,'token' => $randomString]);
        }else{
            return Response()->json(['status' => 'failure', 'message' => 'Invalid username or password']);
        }
    }


    public function getAllUser(Request $request){
        try{
            if(!empty($request->name)){
                $objUserData = User::where('name','LIKE',"%{$request->name}%")->get();
            }else{
                $objUserData = User::get();
            }
           
                if($objUserData->isNotEmpty()){
                    $content =[
                        'status' =>200,
                        'message' =>'data found successsfuly',
                        'data' => $objUserData,
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

    public function deleteUser(Request $request){
        try{
            $intUserId = $request->user_id;
            $deleteUser = User::where('id',$intUserId)->delete();
            
            $content =[
                'status' =>200,
                'message' =>'User deleted successsfuly',
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

    public function getUserDetailsByID(Request $request){
        try{
             $objUserData = User::where('id',$request->user_id)->first();
               if($objUserData){
                    $content =[
                        'status' =>200,
                        'message' =>'Data found successsfuly',
                        'data' => $objUserData,
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
                    'message' =>$e->message
                ];
            }
        return response()->json($content);
    }

    public function updateUserDetails(Request $request){
        try{
             $now = Carbon::now();
             $objUpdateUser = User::find($request->user_id);
             $objUpdateUser->name = $request->name;
             $objUpdateUser->email = $request->email;
             $objUpdateUser->mobile_number = $request->mobile_number;
             $objUpdateUser->type = $request->type;
             $objUpdateUser->updated_at = $now;
             $objUpdateUser->save();  

               $content =[
                        'status' =>200,
                        'message' =>'User updated successsfuly',
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

    public function updateUserStatus(Request $request){
        try{
             $now = Carbon::now();
             $objUpdateUser = User::find($request->user_id);
             $objUpdateUser->status = $request->status;
             $objUpdateUser->updated_at = $now;
             $objUpdateUser->save();  
             $content =[
                'status' =>200,
                'message' =>'User status updated successsfuly',
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

    public function updateUserProfile(Request $request){
        try{
            $rules = [
                'name' => 'required',
                'mobile_number' => 'required',
                'email' => 'required',
                ];

            $messages = [
               'name.required' => 'Name is required.',
               'mobile_number.required' => 'Mobile number is required.',
               'email.required' => 'Email is required.',
            ];
           $validator = Validator::make( $request->all(), $rules, $messages );

           if ( $validator->fails() ) 
           {
            return [
                'status' => 201, 
                'message' => $validator->errors(),
                ];
            }

            // $name = $request->file('file');
            // if ($request->hasFile('file')) {
            //    $image = $request->file('file');
            //    $filename = $image->getClientOriginalName();
            //    // $fileUrl = Storage::disk('public')->put('image', $image);
            //    $path = Storage::putFileAs('photos', new File('D:\product-app\product-app\public'), 'photo.jpg');

            // }
          
            $now = Carbon::now();
            $objUpdateUserProfile = User::find($request->user_id);
            $objUpdateUserProfile->name = $request->name;
            $objUpdateUserProfile->email = $request->email;
            $objUpdateUserProfile->mobile_number = $request->mobile_number;
            $objUpdateUserProfile->updated_at = $now;
            $objUpdateUserProfile->save();  

            $content =[
                'status' =>200,
                'message' =>'User profile has been updated successsfuly',
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
