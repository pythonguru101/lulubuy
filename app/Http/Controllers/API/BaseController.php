<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{

    /**
     * handle success response
     */
    public function handleResponse($result, $msg, $code = 200)
    {
        $res = [
            'success' => true,
            'data' => $result,
            'message' => $msg,
        ];

        return response()->json($res, $code);
    }

    /**
     * handle error responses
     */
    public function handleError($msg, $errors = [], $code = 404): JsonResponse
    {
        $res = [
            'success' => false,
            'message' => $msg,
        ];

        if (!empty($errors)) {
            $res['data'] = $errors;
        }

        return response()->json($res, $code);

    }
}
