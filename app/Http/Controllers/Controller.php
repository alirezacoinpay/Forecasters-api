<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use \Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

abstract class Controller
{
    //

    public function success($data = [], string $message = '', int $status = 200): JsonResponse
    {
        if ($data instanceof JsonResource) {
            $data = $data->response()->getData(true);
        }
        if ($data instanceof Collection) {

            $data = $data->toArray();
        }

        if (!isset($data['meta']) && is_array($data) && array_key_exists('data', $data)) {
            $data = $data['data'];
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => __($message),
        ], $status);
    }



    public function error(string $message = '', $data = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => __($message),
        ], $status);
    }

}
