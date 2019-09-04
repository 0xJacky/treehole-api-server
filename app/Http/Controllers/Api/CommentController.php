<?php


namespace App\Http\Controllers\Api;


use App\Models\Comment;
use App\Models\Post;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CommentController extends Controller
{
    use Helpers;

    public function __construct()
    {
        //
    }

    public function store(Request $request, Comment $comment): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required',
            'post_id' => 'required|uuid',
            'parent' => 'uuid'
        ]);

        $data = [
            'content' => $request['content'],
            'post_id' => $request['post_id']
        ];

        if (isset($request['parent'])) {
            $data['parent'] = $request['parent'];
        }

        $data = $comment->create($data);

        return response()->json(['id' => $data['id']], Response::HTTP_CREATED);

    }

    public function destroy(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);

        if ($this->user()) {
            Comment::destroy($request['id']);
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);
    }

}
