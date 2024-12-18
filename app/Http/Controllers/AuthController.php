<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    // الطلبات الاجبارية 

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile'=>'required|unique:users|min:10|max:10',
            'password'=>'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Message' => 'Failed to Create Account','Errors' => $validator->errors()], 422); 
                 }

                 Log::info('User Password (Unhashed): ' . $request->password);
        
        $user = User::create(['mobile' =>$request->mobile,'password'=>Hash::make($request->password),]);

        //$token=JWTAuth::fromUser($user);

        return response()->json(['Message'=>'Account Created Successfully','User Data'=>$user],201);
          

        }


        public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'mobile' =>'required|regex:/^[0-9]{10}$/',
        'password' =>'required|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'Message' => 'Failed To Login to Your Account',
            'Errors' => $validator->errors()
        ], 422);
    }

    $user_data = $request->only('mobile','password');
    $token = JWTAuth::attempt($user_data);
    if (!$token) {
        return response()->json(['Message' =>'Invalid Data , Please try Again'],401);
    }

    return response()->json(['Message' =>'Login successful','token'=>$token,], 200);
}

public function logout()
{

    $token=JWTAuth::invalidate(JWTAuth::getToken());

    if(!$token)
    {
        return response()->json(['Error Message'=>'An Error Happened Please Try Again..']);
    }
    
    return response()->json(['Message'=>'Logged Out Successfully']);

}



public function update_profile(Request $request)
{
    
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'Message' => 'Profile Not Changed',
            'Errors' => 'User is not login',
        ],401);
    }

    $validator = Validator::make($request->all(), [
        'first_name'=>'nullable|string',
        'last_name'=>'nullable|string',
        'profile_picture'=>'nullable|string',
        'location'=>'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'Message'=>'Profile Not Changed!',
            'Errors'=> $validator->errors()
        ],422);
    }

    $user->update($request->only(['first_name', 'last_name', 'profile_picture', 'location']));

    return response()->json([
        'Message' => 'Profile updated successfully.',
        'User Data'=>$user,
    ]);
}



}

