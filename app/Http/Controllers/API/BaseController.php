<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\JsonResponse;
use Kreait\Firebase\Database;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

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

    /**
     * @return Database
     */
    public function getDb(): Database
    {
        $serviceAccount = ServiceAccount::fromJsonFile(storage_path(env('FIREBASE_CREDENTIALS_PATH')));
        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URI'))
            ->create();

        return $firebase->getDatabase();
    }
}
