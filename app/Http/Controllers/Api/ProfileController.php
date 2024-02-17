<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;

class ProfileController extends Controller
{
    public function change_password(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validations fails',
                'errors'=>$validator->errors()
            ],422);
        }
        $user=$request->user();
        if(Hash::check($request->old_password,$user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'password successfully updated',
            ],200);
        }else{
            return response()->json([
                'message' => 'old password does not matched',
            ],400);
        }
        
}
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=>'User successfully logged out',
        ],200);

    }
}