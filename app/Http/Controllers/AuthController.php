<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //create register function
    public function register(Request $request)
    {
        // return ('Hello'); 
        // return response(["message"=>"ok"], 200);

        // collect the requested data and validate it 
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password'=> [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
                ]
            ]);

        // create and save the validate data in the user schema
        $user = User::create([
            'name'=> $data['name'],
            'email'=> $data['email'],
            'password'=>Hash::make($data['password']),

        ]);

        //tokenize all data 
        $token = $user->createToken('main')->plainTextToken;

        //return the response
        return response([
            'user' => $user,
            'token'=> $token
        ], 200);
    }

    // create login function 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean' // "remember me" checkbox 
        ]);

        $remember = $credentials['remember'] ?? false; // false by default 
        unset($credentials['remember']);
        
        //The attempt method will return true if authentication was successful. Otherwise, false will be returned.
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }
        // give the user authorize to User model
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}