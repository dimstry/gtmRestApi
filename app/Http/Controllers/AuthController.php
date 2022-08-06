<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required'
        ]);

        $id         = bin2hex(random_bytes(4)) . "-" . bin2hex(random_bytes(4)) . "-" . date('YmdHisms');
        $name       = $validated['username'];
        $email      = $validated['email'];
        $password   = Hash::make($validated['password']);
        
        $register = User::create([
            'id'        => $id,
            'username'  => $name,
            'email'     => $email,
            'password'  => $password
        ]);

        if ($register) {
            return response()->json([
                'success' => true,
                'massage' => 'Register Success',
                'data' => $register
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'massage' => 'Register Fail!!',
                'data' => ''
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $validated['email'];
        $password = $validated['password'];

        $user = User::where('email', $email)->first();
        var_dump($user);

        if (Hash::check($password, $user->password)) {
            return response()->json([
                'success' => true,
                'massage' => 'Login success',
                'data' => [
                    'user' => $user,
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'massage' => 'Login fail',
                'data' => ''
            ], 400);
        }
    }


}