<?php

namespace App\Support\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\ResponseFactory;

class Response
{
    /**
     * Json Response.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * API resource helper.
     *
     * @var \App\Support\Http\Resource
     */
    protected $resource;

    /**
     * HTTP status code.
     *
     * @var int
     */
    protected $statusCode = JsonResponse::HTTP_OK;

    /**
     * Validation errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Response message.
     *
     * @var string
     */
    protected $message;

    /**
     * Response messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Response constructor.
     *
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param  \App\Support\Http\Resource                     $resource
     */
    public function __construct(ResponseFactory $response, Resource $resource)
    {
        $this->response = $response;
        $this->resource = $resource;
    }

    /**
     * Return a 201 response with the given created item.
     *
     * @param  mixed        $item
     * @param  string|null  $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function withCreated($item = null, string $resource = null)
    {
        $this->setStatusCode(JsonResponse::HTTP_CREATED);

        if (is_null($item)) {
            return $this->json();
        }

        return $this->item($item, $resource);
    }

    /**
     * Return a 404 response.
     *
     * @param  string|null  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function withNotFound(string $message = null): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_NOT_FOUND
        )
            ->withMessage($message ?? $this->getCommonMessage('not_found'))
            ->json();
    }

    /**
     * Make a 204 response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withNoContent(): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_NO_CONTENT
        )->json();
    }

    /**
     * Return a 429 response.
     *
     * @param  string|null  $message
     * @param  array        $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function withTooManyRequests(
        string $message = null,
        array $data = []
    ): JsonResponse {
        return $this->setStatusCode(
            JsonResponse::HTTP_TOO_MANY_REQUESTS
        )
            ->withMessage($message ?? $this->getCommonMessage('too_many_requests'))
            ->json($data);
    }

    /**
     * Return a 401 response.
     *
     * @param  string|null  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function withUnauthorized(string $message = null): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_UNAUTHORIZED
        )
            ->withMessage($message ?? $this->getCommonMessage('unauthorized'))
            ->json();
    }

    /**
     * Return a 403 response.
     *
     * @param  string|null  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function withForbidden(string $message = null): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_FORBIDDEN
        )
            ->withMessage($message ?? $this->getCommonMessage('forbidden'))
            ->json();
    }

    /**
     * Return a 500 response.
     *
     * @param  string|null  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function withInternalServerError(string $message = null): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        )
            ->withMessage($message ?? $this->getCommonMessage('internal_server_error'))
            ->json();
    }

    /**
     * Return a 202 response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function withAccepted(string $message = ''): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_ACCEPTED
        )
            ->withMessage($message)
            ->json();
    }

    /**
     * Return a 422 response.
     *
     * @param  string|null  $message
     * @param  array|null   $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function withInvalid(
        string $message = null,
        array $errors = null
    ): JsonResponse {
        if ($errors) {
            $this->withErrors($errors);
        }

        return $this->setStatusCode(
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )
            ->withMessage($message ?? $this->getCommonMessage('invalid'))
            ->json();
    }

    /**
     * Return a 409 response.
     *
     * @param  string|null  $message
     * @param  mixed        $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function withConflict(string $message = null, $data = []): JsonResponse
    {
        return $this->setStatusCode(
            JsonResponse::HTTP_CONFLICT
        )
            ->withMessage($message ?? $this->getCommonMessage('conflict'))
            ->json($data);
    }

    /**
     * Make a JSON response with the transformed item.
     *
     * @param  mixed        $item
     * @param  string|null  $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function item($item, string $resource = null): JsonResponse
    {
        return $this->json(
            $this->resource->item($item, $resource)
        );
    }

    /**
     * Make a JSON response with the transformed items.
     *
     * @param  mixed        $items
     * @param  string|null  $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection($items, string $resource = null): JsonResponse
    {
        return $this->json(
            $this->resource->collection($items, $resource)
        );
    }

    /**
     * Make a JSON response.
     *
     * @param  mixed  $data
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], array $headers = []): JsonResponse
    {
        $responseData = [];

        if (!empty($this->errors)) {
            $responseData['errors'] = $this->errors;
        }

        if (!empty($this->message)) {
            $responseData['message'] = $this->message;
        }

        if (!empty($this->messages)) {
            $responseData['messages'] = $this->messages;
        }

        if (!empty($data) || $data === null) {
            $responseData['data'] = $data;
        }

        return $this->response->json($responseData, $this->statusCode, $headers);
    }

    /**
     * Alias for $this->json().
     *
     * @param  mixed  $data
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data = [], array $headers = []): JsonResponse
    {
        return $this->json($data, $headers);
    }

    /**
     * Make a JSON response with a message.
     *
     * @param  string  $message
     * @return self
     */
    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Make a JSON response with messages.
     *
     * @param  array  $messages
     * @return self
     */
    public function withMessages(array $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Append errors to Response.
     *
     * @param  array  $errors
     * @return self
     */
    public function withErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Set HTTP status code.
     *
     * @param  int  $statusCode
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the common response messages.
     *
     * @param  string  $name
     * @return string|null
     */
    public function getCommonMessage(string $name): ?string
    {
        return trans('common.response.' . $name);
    }
}
