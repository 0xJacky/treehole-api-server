<?php


namespace App\Http\Controllers;


use App\Handler\ImageUploadHandler;
use App\Models\Comment;
use App\Models\Post;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Webpatser\Uuid\Uuid;

class CommentController extends Controller
{
    use Helpers;

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required',
            'post_id' => 'required|uuid',
            'parent' => 'uuid'
        ]);

        $data = [
            'content' => $request['content'],
            'post_id' => $request['post_id'],
            'upload_uuid' => (string)Uuid::generate(4)
        ];

        if (isset($request['parent'])) {
            $data['parent'] = $request['parent'];
        }

        $data = Comment::create($data);

        $post = Post::find($request['post_id']);
        $post->comments++;
        $post->save();

        return response()->json([
            'id' => $data['id'],
            'upload_uuid' => $data['upload_uuid']
        ],
            Response::HTTP_CREATED);

    }

    public function destroy(Request $request, ImageUploadHandler $uploader): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);

        if ($this->user()) {
            $comment = Comment::find($request['id']);

            $post = Post::find($comment->post_id);
            $post->comments--;
            $post->save();

            if ($comment->upload_id) {
                $uploader->delete($comment->upload_id);
            }

            $comment->delete();
            
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);
    }

}
