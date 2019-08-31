<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required']);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return response()->json(Auth::user(), Response::HTTP_OK);
        } else {
            return response()->json(['error' => '用户名或密码错误'], Response::HTTP_FORBIDDEN)->cookie('');
        }
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();
        return response()->json(['msg' => '您已成功注销'], Response::HTTP_OK);
    }
}
