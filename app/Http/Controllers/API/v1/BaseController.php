<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'status'    => 1,
            'message'   => $message,
            'data'      => $result
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status'    => 0,
            'message'   => $error,
        ];

        if (!empty($errorMessages))
        {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
