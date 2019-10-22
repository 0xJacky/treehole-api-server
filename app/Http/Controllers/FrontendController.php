<?php


namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Settings;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;

class FrontendController extends Controller
{

    public function home(): JsonResponse
    {

        $settings = Settings::first();
        $data['notice'] = $settings->notice;

        $data['categories'] = Category::orderBy('order', 'asc')->get();

        $data['posts'] = Post::with('category', 'upload')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($data, Response::HTTP_OK);
    }

    public function about(): JsonResponse
    {
        $settings = Settings::first();
        $data['about'] = $settings->about;

        return response()->json($data, Response::HTTP_OK);
    }

}
