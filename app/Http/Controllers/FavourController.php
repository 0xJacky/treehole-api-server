<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FavourController extends Controller
{
    public function like(Request $request): JsonResponse
    {
        $this->validate($request, [
            'post_id' => 'sometimes|required|uuid',
            'comment_id' => 'sometimes|required|uuid',
            'rollback' => 'required'
        ]);

        if ($request->has('post_id')) {
            $object = Post::find($request['post_id']);
        } else if ($request->has('comment_id')) {
            $object = Comment::find($request['comment_id']);
        } else {
            return response()->json(['message' => '参数错误'], Response::HTTP_BAD_REQUEST);
        }

        $object->likes = $object->likes + 1;
        if ($request['rollback'] === 'true') {
            $object->dislikes = $object->dislikes - 1;
        }
        $object->save();

        return response()->json(['message' => '点赞成功',
            'likes' => $object->likes,
            'dislikes' => $object->dislikes], Response::HTTP_OK);

    }

    public function dislike(Request $request): JsonResponse
    {
        $this->validate($request, [
            'post_id' => 'sometimes|required|uuid',
            'comment_id' => 'sometimes|required|uuid',
            'rollback' => 'required'
        ]);

        if ($request->has('post_id')) {
            $object = Post::find($request['post_id']);
        } else if ($request->has('comment_id')) {
            $object = Comment::find($request['comment_id']);
        } else {
            return response()->json(['message' => '参数错误'], Response::HTTP_BAD_REQUEST);
        }

        $object->dislikes = $object->dislikes + 1;
        if ($request['rollback'] === 'true') {
            $object->likes = $object->likes - 1;
        }
        $object->save();

        return response()->json(['message' => '点踩成功',
            'likes' => $object->likes,
            'dislikes' => $object->dislikes], Response::HTTP_OK);

    }
}
