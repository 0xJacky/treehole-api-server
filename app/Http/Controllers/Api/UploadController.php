<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Handler\ImageUploadHandler;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{
    public function store(Request $request, ImageUploadHandler $uploader): JsonResponse
    {
        $this->validate($request, [
            'post_id' => 'sometimes|required|uuid',
            'comment_id' => 'sometimes|required|uuid',
            'upload_uuid' => 'required|uuid'
        ]);
        $t = [];
        /* 文章发布后返回上传 UUID 凭借 UUID 确认身份后才可上传 */
        if ($request->has('post_id')) {
            $t = Post::find($request['post_id']);
        }
        if ($request->has('comment_id')) {
            $t = Comment::find($request['comment_id']);
        }
        if ($t->upload_uuid !== $request->upload_uuid) {
            return response()->json(['message' => '无权上传'], Response::HTTP_FORBIDDEN);
        }
        $file = $request->file('img');
        $data = [
            'success' => false,
            'message' => '上传失败',
            'url' => ''
        ];
        if (!$file->isValid()) {
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $upload = $uploader->store($file);
        if ($upload['status']) {
            $data = [
                'success' => true,
                'message' => '上传成功',
                'url' => $upload['url'],
                'id' => $upload['id']
            ];
            /* 销毁上传 UUID */
            $t->upload_uuid = null;
            $t->upload_id = $upload['id'];
            $t->save();
            return response()->json($data, Response::HTTP_OK);
        }
        return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
