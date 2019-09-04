<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Helpers;

    public function info(): JsonResponse
    {
        return response()->json($this->user(), Response::HTTP_OK);
    }

    public function update(Request $request): JsonResponse
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'email|max:255'
        ]);

        $data = [
            'name' => $request['name'],
            'email' => $request['email']
        ];

        $this->validate($request, [
            'id' => 'uuid'
        ]);

        $user = User::find($this->user()->id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        if ($request->has('password')) {
            $user->password = bcrypt($request['password']);
        }
        $user->save();

        return response()->json(['msg' => '更新成功'], Response::HTTP_OK);

    }
}
