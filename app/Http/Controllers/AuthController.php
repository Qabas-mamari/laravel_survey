<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    //create register function
    public function register(Request $request)
    {
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
            'password'=>$bcrypt($data['password'])

        ]);

        //tokenize all data 
        $token = $user->createTable('main')->plainTestToken;

        //return the response
        return response([
            'user' => $user,
            'token'=> $token
        ]);
    }
}
