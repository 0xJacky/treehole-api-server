<?php


namespace App\Http\Controllers\Api;

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

    public function get(Request $request, Post $post): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);
        $r = $post->with('category')
            ->find($request['id']);

        $comments['root'] = [];

        $comments['children'] = $r->getComments();
        if (isset($comments['children'][''])) {
            $comments['root'] = $comments['children'][''];
            unset($comments['children']['']);
        }
        $r['comments'] = $comments;

        return response()->json($r, Response::HTTP_OK);
    }

    public function store(Request $request, Post $post): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required',
            'category_id' => 'required|uuid'
        ]);

        $data = $post->create([
            'content' => $request['content'],
            'category_id' => $request['category_id']
        ]);

        return response()->json(['id' => $data['id']], Response::HTTP_CREATED);

    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);

        if (Auth::user()) {
            $post->destroy($request['id']);
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);
    }

    public function get_list(Request $request): JsonResponse
    {
        $data = Post::with('category')
            ->orderBy('created_at', 'desc');

        if ($request->has('category')) {
            $data = $data->where('category_id', $request['category']);
        }
        $data = $data->paginate(10);

        return response()->json($data, Response::HTTP_OK);
    }

}
