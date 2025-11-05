<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgetPasswordRequest;

class PasswordController extends Controller
{
    public function __construct(private AuthService $autheService){}

    public function forgetPassword(ForgetPasswordRequest $request){        
        return $this->autheService->resetPasswordLink($request);
    }

    public function passwordReset(ResetPasswordRequest $request){
        return $this->autheService->resetPassword($request);
    }
}
