<?php

namespace App\Traits;

/**
 * Trait ApiResponse
 * Provides methods for standardized API responses.
 */
trait ApiResponse
{
    /**
     * Returns a success JSON response.
     *
     * @param string $message The success message. Default is 'Done'.
     * @param mixed|null $data The data to include in the response. Default is null.
     * @param int $statusCode The HTTP status code. Default is 200.
     * @return \Illuminate\Http\JsonResponse The JSON response object.
     */
    public function success($message = 'Done', $data = null, $statusCode = 200)
    {
        return response()->json([
            'response' => [
                'data' => $data,
                'status' => true,
                'code' => $statusCode,
                'message' => $message
            ]
        ]);
    }

    /**
     * Returns an error JSON response.
     *
     * @param string $message The error message.
     * @param int $statusCode The HTTP status code. Default is 500.
     * @return \Illuminate\Http\JsonResponse The JSON response object.
     */
    public function error($message, $statusCode = 500)
    {
        return response()->json([
            'response' => [
                'status' => false,
                'code' => $statusCode,
                'message' => $message
            ],
        ], $statusCode);
    }

    /**
     * Returns a validation error JSON response.
     *
     * @param array $errors The validation errors.
     * @return \Illuminate\Http\JsonResponse The JSON response object.
     */
    public function validationErrors($errors)
    {
        return response()->json([
            'response' => [
                'status' => false,
                'code' => 422,
                'message' => 'Please make sure your data is correct',
                'errors' => $errors
            ],
        ], 422);
    }
}
