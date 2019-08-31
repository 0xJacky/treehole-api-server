<?php


namespace App\Http\Controllers;

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
        $result['categories'] = $category->get();
        $result['posts'] = $post->with('category')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($result, Response::HTTP_OK);
    }

}
