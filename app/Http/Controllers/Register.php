<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;

class Register extends Controller
{
    /**
     * Show the account details for the given user.
     *
     * @return Response
     */


    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|max:256',
            'country' => 'required|max:100',
            'trading_platform' => 'required|max:100',
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|max:20',
            'password' => 'required|max:90',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed', 
                'errors' => $validator->messages()], 400);
        } else {
            $email= $request->email;
            return response()->json(['message' => $email], 200);
        }
    }
}
