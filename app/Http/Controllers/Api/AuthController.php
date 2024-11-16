<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResistrationRequest;

class AuthController extends Controller
{

    public function register(ResistrationRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return res_data($user,$token,'Registeration Successful');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('password');

        if (filter_var($request->account, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->account;
        } else {
            $credentials['username'] = $request->account; // If using a username alternative.
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $user = auth()->user();

        $token = $user->createToken('Personal Access Token')->accessToken;

        $data = []; // Your additional data to return if any
        return res_data($data, $token, 'Login Successful');
    }
    public function logout(Request $request){
        try {
            $user = auth('api')->user(); // Explicitly use the 'api' guard
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $user->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}
