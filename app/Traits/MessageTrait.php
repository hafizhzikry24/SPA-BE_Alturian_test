<?php
namespace App\Traits;

trait MessageTrait
{
public function successMessage($message, $data = [], $paginationData = null)
{
    $response = [
        'message' => $message,
        'data' => $data,
    ];

    if ($paginationData) {
        $response['pagination'] = [
            'current_page' => $paginationData->currentPage(),
            'total_pages' => $paginationData->lastPage(),
            'per_page' => $paginationData->perPage(),
            'total' => $paginationData->total(),
        ];
    }

    return response()->json($response, 200);
}

    public function errorMessage($message, $error = null, $statusCode = 500)
    {
        return response()->json([
            'message' => $message,
            'error' => $error
        ], $statusCode);
    }

    public function validationErrorMessage($errors)
    {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422);
    }
}
