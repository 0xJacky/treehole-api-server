<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'post_id' => 'uuid',
            'comment_id' => 'uuid'
        ]);

        if ($request->has('post_id')) {
            $data['post_id'] = $request['post_id'];
        } elseif ($request->has('comment_id')) {
            $data['comment_id'] = $request['comment_id'];
        } else {
            return response()->json(['msg' => '参数错误'], Response::HTTP_BAD_REQUEST);
        }

        $data = Report::create($data);

        return response()->json(['id' => $data['id']], Response::HTTP_CREATED);

    }

    public function get_list(Request $request): JsonResponse
    {
        $data = Report::with('post', 'comment');
        if ($request->has('status')) {
            $this->validate($request, [
                'status' => 'integer'
            ]);
            $data = $data->where('status', $request['status']);
        }
        $data = $data->paginate(10);
        return response()->json($data, Response::HTTP_OK);
    }

    public function dispose(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'uuid'
        ]);

        $report = Report::find($request['id']);
        $report->status = 1;
        $report->save();

        return response()->json(['msg' => '操作成功'], Response::HTTP_OK);
    }

    public function withdraw(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'uuid'
        ]);

        $report = Report::find($request['id']);
        $report->status = 0;
        $report->save();

        return response()->json(['msg' => '操作成功'], Response::HTTP_OK);
    }
}
