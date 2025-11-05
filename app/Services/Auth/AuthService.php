<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordLinkMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function created($request){
            
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        
        $token = auth()->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User Registered Successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
        ]);
    }
    public function loginProcess($request){
        
        $credentials = $request->only('email', 'password');
        if(!$token = auth()->attempt($credentials)){
            return response()->json([
                'status' => 'error',
                'message' => 'Credential was Invalid !',            
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Login Successfull',
            'data' => [
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
        ]);
    }

    public function resetPasswordLink($request){
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User Not Found !',
            ], Response::HTTP_NOT_FOUND);
        }

        //generate random token
        $token = Str::random(60);

        //insert the token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );        
        Mail::to($user->email)->send(new ResetPasswordLinkMail($user,$token));
                
        return response()->json([
                'status' => 'success',
                'message' => 'Password Reset Link Sent to your email',
            ], Response::HTTP_OK);
    }

    public function resetPassword($request){
        $email = $request->email;
        $password = $request->password;
        $token = $request->token;

        $tokenRecord = DB::table('password_reset_tokens')
                        ->where('email', $email)
                        ->first();

        if(!$tokenRecord || !Hash::check($token, $tokenRecord->token)){
            return response()->json([
                'status' => 'error',
                'message' => 'Token was Invalid !',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User Not Found !',
            ], Response::HTTP_NOT_FOUND);
        }
        
        if(Carbon::parse($tokenRecord->created_at)->addHour()->isPast()){
            DB::table('password_reset_tokens')
                        ->where('token', $token)
                        ->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Token/Time has Expired !',
            ], Response::HTTP_NOT_FOUND);
        }

        $user->update(['password' => $password]);
        DB::table('password_reset_tokens')
                        ->where('token', $token)
                        ->delete();

        return response()->json([
                'status' => 'success',
                'message' => 'Password has been reset successfully !',
            ], Response::HTTP_OK);
    }
}
