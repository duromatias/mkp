<?php

namespace App\Modules\Onboarding\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Onboarding\Auth\AuthBusiness;
use function response;

class AuthController extends Controller {
    
    public function login(LoginRequest $request) {
        
        $response = AuthBusiness::loginConTokenOnboarding(
            $request->header('x-authorization-dc'), 
            $request->get('email')
        );
        
        return response()->json($response);
    }
}
