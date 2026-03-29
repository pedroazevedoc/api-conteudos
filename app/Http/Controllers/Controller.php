<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function handleSuccessResponse(string $message = 'Mensagem genérica de successo', $data = null, int $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'payload' => $data,
        ], $statusCode);
    }

    protected function handleErrorResponse(string $message, $details = null, int $statusCode)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'details' => $details,
        ], $statusCode);
    }
}
