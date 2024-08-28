<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{
    public function sendResponse($result, $message, $code = 200): JsonResponse
    {
        return Response::json($this->makeResponse($message, $result), $code);
    }

    public function sendError($error, $code = 404): JsonResponse
    {
        return Response::json($this->makeError($error), $code);
    }

    public function makeResponse($message, $data): array
    {
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    public function makeError($message, array $data = []): array
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }

}
