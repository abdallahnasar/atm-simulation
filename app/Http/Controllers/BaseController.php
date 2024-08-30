<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{
    public function sendResponse($result, $message, $code = 200, $paginatedResponse = false): JsonResponse
    {
        if ($paginatedResponse) {
            $response = $this->makePaginatedResponse($message, $result);
        } else {
            $response = $this->makeResponse($message, $result);
        }

        return Response::json($response, $code);
    }

    public function makePaginatedResponse($message, $paginator): array
    {
        return [
            'success' => true,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ],
            'message' => $message,
        ];
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
