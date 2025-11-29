<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecommendationException extends Exception
{
    /**
     * Structured error data returned from the API or service.
     */
    public readonly array $body;

    /**
     * RecommendationException constructor.
     *
     * @param string $message Exception message
     * @param int $code HTTP status code
     * @param array $body Structured error data
     */
    public function __construct(string $message = "", int $code = 500, array $body = [])
    {
        $this->body = Arr::get($body, 'data', $body);

        parent::__construct(
            $message ?: Arr::get($this->body, 'message', 'An error occurred'),
            $code > 0 ? $code : 500 // ensure valid HTTP code
        );
    }


    /**
     * Render the exception as a JSON HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error'   => Arr::get($this->body, 'error', $this->message),
            'status'  => $this->code,
            'message' => Arr::get($this->body, 'message', $this->message),
        ], $this->code);
    }
}
