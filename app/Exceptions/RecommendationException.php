<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecommendationException extends Exception
{
    public readonly array $body;

    public function __construct(string $message = "", int $code = 0, array $body = [])
    {
        parent::__construct($message, $code);

        $this->body = Arr::get($body, 'data', $body);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => Arr::get($this->body, 'message', $this->message),
            'status' => $this->code,
            'message' => Arr::get($this->body, 'trace', $this->message),
        ], $this->code);
    }
}
