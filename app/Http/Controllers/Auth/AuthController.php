<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService){}

    public function register(RegistrationRequest $request){        
        return $this->authService->created($request);
    }

    public function login(LoginRequest $request){
        return $this->authService->loginProcess($request);
    }
}
