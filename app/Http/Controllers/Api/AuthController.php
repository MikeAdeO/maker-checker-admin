<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   public function AdminLogin(Request $request){
        try {
            $validate = Validator::make($request->all(), [
               "email" => "required|email|exists:admins,email",
               "password" => "required"
            ]);

           if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validate->errors()
            ], 422);
           }

           if(Auth::guard('admin')->attempt(["email" => $request->email, 'password' => $request->password])){
            $user = Auth::guard('admin')->user();

            $token = $user->createToken('admin-token', ['admin'])->plainTextToken;
            return response()->json(['token' => $token]);
           }


           $admin = Admin::where('email', $request->email)->first();
           return response()->json([
                "status" => true,
                "message" => "user logged in Successfully",
                'token' => $admin->createToken('API TOKEN')->plainTextToken
           ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
   }

   public function adminLogOut(){
    $admin = request()->user();
    $admin->tokens()->delete();

    return response()->json(['message' => "logged out"], 200);
   }
}
