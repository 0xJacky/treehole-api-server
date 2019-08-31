<?php


namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;
use Auth;

class PostController extends Controller
{
    public function __construct()
    {
        //
    }

    public function store(Request $request, Post $post): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required',
            'category_id' => 'required'
        ]);

        $result = $post->create([
            'content' => $request['content'],
            'category_id' => (int)$request['category_id']
        ]);

        return response()->json(['id' => $result['id']], Response::HTTP_CREATED);

    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        if (Auth::user()) {
            $post->destroy($request['id']);
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);
    }

    public function get_list(Post $post): JsonResponse
    {
        $result = $post->with('category')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($result, Response::HTTP_OK);
    }

}
