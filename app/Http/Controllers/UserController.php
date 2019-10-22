<?php

namespace App\Http\Controllers;

use App\Models\Post;
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

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        $data = User::Create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        return response()->json([
            'id' => $data['id']
        ], Response::HTTP_CREATED);

    }

    public function update(Request $request): JsonResponse
    {

        $this->validate($request, [
            'id' => 'uuid',
            'name' => 'required',
            'email' => 'email|max:255'
        ]);

        $data = [
            'name' => $request['name'],
            'email' => $request['email']
        ];

        $user = User::find($request['id']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        if ($request->has('password')) {
            $user->password = bcrypt($request['password']);
        }
        $user->save();

        return response()->json(['msg' => '更新成功'], Response::HTTP_OK);

    }

    public function get_list(): JsonResponse
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return response()->json($users, Response::HTTP_OK);

    }

    public function destroy(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);

        if ($this->user()) {
            $user = User::find($request['id']);
            $user->delete();
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);
    }

}
