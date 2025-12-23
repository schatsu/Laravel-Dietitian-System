<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait Responder
{
    protected function success(
        object|array|null $data = null,
        ?string $message = null,
        int $code = ResponseAlias::HTTP_OK
    ): JsonResponse {
        $response = [
            'status'  => $code,
            'success' => true,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data instanceof JsonResource) {
            return $data
                ->additional(array_merge($data->additional ?? [], $response))
                ->response()
                ->setStatusCode($code);
        }

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Error response
     */
    protected function error(
        string $message,
        int $code = ResponseAlias::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'status'  => $code,
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }


    protected function paginated(
        LengthAwarePaginator $paginator,
        ?string $message = null,
        ?string $resource = null
    ): JsonResponse {
        $data = $resource
            ? $resource::collection($paginator->items())
            : $paginator->items();

        $response = [
            'status'  => ResponseAlias::HTTP_OK,
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, ResponseAlias::HTTP_OK);
    }

    protected function created(
        object|array|null $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return $this->success($data, $message, ResponseAlias::HTTP_CREATED);
    }

    protected function accepted(
        object|array|null $data = null,
        string $message = 'Request accepted'
    ): JsonResponse {
        return $this->success($data, $message, ResponseAlias::HTTP_ACCEPTED);
    }

    protected function noContent(): Response
    {
        return response()->noContent();
    }

    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, ResponseAlias::HTTP_NOT_FOUND);
    }

    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, ResponseAlias::HTTP_FORBIDDEN);
    }

    protected function validationError(
        mixed $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return $this->error(
            $message,
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            $errors
        );
    }

    protected function serverError(
        string $message = 'Internal server error'
    ): JsonResponse {
        return $this->error(
            $message,
            ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
