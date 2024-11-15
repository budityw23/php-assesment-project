<?php

namespace Handler;

class ResponseHandler
{
    public static function success($data = null, int $code = 200, ?string $message = null): array
    {
        $response = [
            'status' => 'success',
            'code' => $code
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($message !== null) {
            $response['message'] = $message;
        }

        return $response;
    }

    public static function error(string $message, int $code = 400, $errors = null): array
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    public static function notFound(string $message = 'Resource not found'): array
    {
        return self::error($message, 404);
    }

    public static function validationError(string $message = 'Validation failed', array $errors = []): array
    {
        return self::error($message, 422, $errors);
    }

    public static function serverError(string $message = 'Internal server error'): array
    {
        return self::error($message, 500);
    }

    public static function unauthorized(string $message = 'Unauthorized'): array
    {
        return self::error($message, 401);
    }

    public static function forbidden(string $message = 'Forbidden'): array
    {
        return self::error($message, 403);
    }
}
