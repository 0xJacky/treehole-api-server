<?php


namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct()
    {
        //
    }

    public function store(Request $request, Comment $comment): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required',
            'post_id' => 'required',
            'parent' => 'integer'
        ]);

        $data = [
            'content' => $request['content'],
            'post_id' => (int)$request['post_id']
        ];

        if (isset($request['parent'])) {
            $data['parent'] = $request['parent'];
        }

        $result = $comment->create($data);

        return response()->json(['id' => $result['id']], Response::HTTP_CREATED);

    }

    public function get(Request $request, Post $post): JsonResponse
    {
        $comments = $post->find($request['post_id'])
            ->comments()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($comments, Response::HTTP_OK);

    }

}
