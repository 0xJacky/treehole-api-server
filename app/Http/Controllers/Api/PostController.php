<?php


namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Dingo\Api\Routing\Helpers;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;
use Auth;
use Webpatser\Uuid\Uuid;
use App\Handler\ImageUploadHandler;

class PostController extends Controller
{
    use Helpers;

    public function get(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);
        $post = Post::with('category', 'upload')
            ->find($request['id']);

        $comments['root'] = [];

        //$comments['children'] = $post->getComments();
        $c = Comment::where('post_id', $post->id)
            ->with('upload')
            ->get();

        $domain = config('aliyun.domain');

        foreach ($c as $comment) {
            if ($comment->upload_id) {
                $comment->upload->url = $domain . $comment->upload->oss_path . '!article';
            }
            $comments['children'][$comment->parent][] = $comment;
        }


        if (isset($comments['children'][''])) {
            $comments['root'] = $comments['children'][''];
            unset($comments['children']['']);
        }
        if ($post->upload_id) {
            $post->upload->url = config('aliyun.domain') . $post->upload->oss_path . '!article';
        }
        $post['comments'] = $comments;

        return response()->json($post, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required',
            'category_id' => 'required|uuid'
        ]);

        $data = Post::create([
            'content' => $request['content'],
            'category_id' => $request['category_id'],
            'upload_uuid' => (string)Uuid::generate(4)
        ]);

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
            $post = Post::find($request['id']);
            if ($post->upload_id) {
                $uploader->delete($post->upload_id);
            }
            $post->delete();
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);
    }

    public function get_list(Request $request): JsonResponse
    {
        $data = Post::with('category', 'upload')
            ->orderBy('created_at', 'desc');

        if ($request->has('category')) {
            $data = $data->where('category_id', $request['category']);
        }
        $data = $data->paginate(10);

        return response()->json($data, Response::HTTP_OK);
    }

}
