<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function forgetPassword(Request $request){        
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
        //Send reset token by email
        $emailData = [
            'user' => $user,
            'token' => $token,
        ];
        Mail::send('emails.password-reset', $emailData, function($message) use($user){
            $message->to($user->email, $user->name);
            $message->subject('Password Reset Email');
        });

        return response()->json([
                'status' => 'success',
                'message' => 'Password Reset Link Sent to your email',
            ], Response::HTTP_OK);
    }

    public function passwordReset(Request $request){

    }
}
