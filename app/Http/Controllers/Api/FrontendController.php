<?php


namespace App\Http\Controllers\Api;

use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;

class FrontendController extends Controller
{
    public function __construct()
    {
        //
    }

    public function home(Post $post, Category $category): JsonResponse
    {
        $data['categories'] = $category->orderBy('created_at', 'asc')->get();
        $data['posts'] = $post->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($data, Response::HTTP_OK);
    }

}
