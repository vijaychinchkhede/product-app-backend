<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


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
        return Response()->json(['status' => '200', 'message' => 'Successful..!']);
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
}
