<?php

namespace App\Http\Controllers;

use function abort;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use function response;
use Symfony\Component\HttpFoundation\Response as IlluminateResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Our default response scheme.
     *
     * @param array $data
     * @param array $meta
     * @param array $errors
     * @param array $headers
     * @param bool $success
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondStandard($data = [], $meta = [], $errors = [], $headers = [], $success = true): \Illuminate\Http\JsonResponse
    {
        $response = [
            'data' => $data,
            'success' => $success,
            'status_code' => IlluminateResponse::HTTP_OK,
        ];

        if (! empty($meta)) {
            $response['meta'] = $meta;
        }

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, IlluminateResponse::HTTP_OK, $headers);
    }

    /**
     * Respond error.
     *
     * @param string $message
     * @param $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function abortWithError(string $message, $statusCode): \Illuminate\Http\JsonResponse
    {
        $response = [
            'errors' => $message,
            'success' => false,
            'status_code' => $statusCode,
        ];

        abort(IlluminateResponse::HTTP_BAD_REQUEST, $message, $response);
    }

    /**
     * Respond error.
     *
     * @param string $message
     * @param int $customStatusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError(string $message, $customStatusCode = IlluminateResponse::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        $response = [
            'errors' => $message,
            'success' => false,
            'status_code' => $customStatusCode,
        ];

        return response()->json($response, IlluminateResponse::HTTP_BAD_REQUEST);
    }
}
