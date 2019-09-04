<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function get_list(Category $category): JsonResponse
    {
        $data = $category->orderBy('created_at', 'asc')->get();

        return response()->json($data, Response::HTTP_OK);
    }

    public function store(Request $request, Category $category): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        if ($request->has('id')) {
            $c = $category->find($request['id']);
            $c->name = $request['name'];
            $c->save();
            return response()->json(['msg' => '保存成功'], Response::HTTP_OK);
        } else {
            $data = $category->create([
                'name' => $request['name']
            ]);
            return response()->json(['id' => $data['id']], Response::HTTP_CREATED);
        }
    }
}
