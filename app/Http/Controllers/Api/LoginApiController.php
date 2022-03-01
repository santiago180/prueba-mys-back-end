<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;


class LoginApiController extends Controller
{
    /**
     * @author Santiago Torres
     * @param LoginRequest $request
     * @return token
     * 
     * realizamos autenticacion 
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'token'     => $request->user()->createToken($request->name)->plainTextToken,
                'message'   => 'success'
            ]);
        }

        return response()->json([
            'message'   => 'No está autorizado'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data["password"] = bcrypt($data["password"]);
            User::create($data);
            return response()->json([
                'message'   => 'el usuario ha sido registrado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => 'error al registrar usuario'
            ], 401);
        }
    }

}
