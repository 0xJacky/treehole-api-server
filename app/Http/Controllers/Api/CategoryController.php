<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use Helpers;

    public function get_list(Category $category): JsonResponse
    {
        $data = $category->orderBy('order', 'asc')->get();

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

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid'
        ]);

        if ($this->user()) {
            Category::destroy($request['id']);
        } else {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['msg' => '删除成功'], Response::HTTP_OK);

    }
}
