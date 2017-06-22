<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticateApp(Request $request){
        $credentials = base64_decode(
            Str::substr($request->header('Authorization'), 6)
        );

        try {
            list($appKey, $appSecret) = explode(':', $credentials);

            $app = Application::whereKeyAndSecret($appKey, $appSecret)->firstOrFail();
        } catch (\Throwable $e) {
            return response('invalid_credentials', 400);
        }

        if (! $app->is_active) {
            return response('app_inactive', 403);
        }

        return response([
            'token_type' => 'Bearer',
            'access_token' => $app->generateAuthToken(),
        ]); 
    }

    public function authenticateUser(Request $request){}

    public function logoutUser(Request $request){}
}
