<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    use Helpers;

    public function get(): JsonResponse
    {
        return response()->json(Settings::first(), Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        /*$this->validate($request, [
            'notice' => 'required'
        ]);*/
        if (!$this->user()) {
            return response()->json(['msg' => '无权访问'], Response::HTTP_FORBIDDEN);
        } else {
            $settings = Settings::first();
            foreach ($request->all() as $k => $v) {
                $settings->$k = $v;
            }
            $settings->save();
            return response()->json(['msg' => '保存成功'], Response::HTTP_OK);
        }
    }
}
