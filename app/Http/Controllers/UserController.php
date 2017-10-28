<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Register the user in the application
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created user!',
                'user' => $user
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The user was not created',
            ]);
        }
    }

    /**
     * Login the user in the application
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The user and password invalid',
                ], 401);
            }

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'success' => true,
            'message' => 'The user authenticated successfully',
            'user' => $user->name,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request){
        return response()->json([
            'success' => true,
            'message' => 'The user has been logout',
        ], 200);
    }

}
