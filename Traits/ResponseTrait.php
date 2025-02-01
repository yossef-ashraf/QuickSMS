<?php

namespace Fabrikar\Communication\Traits;

trait ResponseTrait
{
    protected function successResponse($data = null, string $message = 'Success'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    protected function errorResponse(string $message, $errors = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }
}