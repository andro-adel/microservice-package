<?php

namespace DD\MicroserviceCore\Classes;

use DD\MicroserviceCore\Enums\HttpRequestStatusEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponses
{
    /**
     * base response function
     * @param $data
     * @param HttpRequestStatusEnum $status
     * @return JsonResponse
     */
    private static function response($data, HttpRequestStatusEnum $status): JsonResponse
    {
        $data['code'] = $status->value;
        return response()->json($data, $status->value);
    }

    /**
     * default success response
     * @param array $data
     * @param string $reason
     * @param string|null $message
     * @param array $additionData
     * @param HttpRequestStatusEnum $status
     * @return JsonResponse
     */
    public static function successResponse(array $data, string $reason, string|null $message = null,
                                           array $additionData = [],
                                           HttpRequestStatusEnum $status = HttpRequestStatusEnum::STATUS_OK)
    : JsonResponse
    {
        return self::response([
            'success' => true,
            'type' => 'success',
            'data' => $data,
            'reason' => $reason,
            'message' => $message ?? __('response_messages.success_message'),
            ...$additionData
        ], $status);
    }

    /**
     * success response without content
     * @return JsonResponse
     */
    public static function successNoContentResponse(): JsonResponse
    {
        return self::response([], HttpRequestStatusEnum::STATUS_SUCCESS_WITH_NO_CONTENT);
    }

    /**
     * resource not modified response
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function notModifiedResponse(string|null $resourceName = null, string|null $message = null,
                                        array $additionData = []): JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'Failure',
            'message' => $message ??
                (($resourceName ?? __('response_messages.default_resource_name'))
                    . ' ' . __('response_messages.not_modified')),
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_NOT_MODIFIED);
    }

    /**
     * bad request response
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function badRequestResponse(string|null $message = null, array $additionData = [],
                                       HttpRequestStatusEnum $status = HttpRequestStatusEnum::STATUS_BAD_REQUEST)
    : JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'General',
            'message' => $message,
            ...$additionData
        ], $status);
    }

    /**
     * un authorized response for permission issues
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function unauthorizedResponse(string|null $message = null, array $additionData = []): JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'Permissions',
            'message' => $message ?? __('response_messages.permission_denied'),
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_UNAUTHORIZED);
    }

    /**
     * unauthenticated response for invalid auth data
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function unauthenticatedResponse(string|null $message = null, array $additionData = []): JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'Unauthenticated',
            'message' => $message ?? __('response_messages.unauthenticated'),
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_FORBIDDEN);
    }

    /**
     * not found resource response
     * @param string|null $resourceName
     * @param array $additionData
     * @param string|null $message
     * @return JsonResponse
     */
    public static function notFoundResponse(string|null $resourceName = null, string|null $message = null,
                                     array $additionData = []): JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'Not Found',
            'message' => $message ??
                (($resourceName ?? __('response_messages.default_resource_name'))
                    . ' ' . __('response_messages.not_found')),
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_NOT_FOUND);
    }

    /**
     * conflict response
     * @param string $type
     * @param array $data
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function conflictsResponse(string $type, array $data, string|null $resourceName = null,
                                      string|null $message = null, array $additionData = [])
    : JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => $type,
            'data' => $data,
            'reason' => 'Failure',
            'message' => $message ??
                (($resourceName ?? __('response_messages.default_resource_name'))
                    . ' ' . __('response_messages.has_conflicts')),
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_CONFLICT);
    }

    /**
     * not valid response for validation issues
     * @param array $errors
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function notValidResponse(array $errors, string|null $message = null, array $additionData = [])
    : JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'Validation',
            'message' => $message ?? __('response_messages.inputs_not_valid'),
            'errors' => $errors,
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_VALIDATIONS_ERROR);
    }

    /**
     * server error response
     * @param string|int $error_code
     * @param string $file
     * @param string|int $line
     * @param string|null $message
     * @param array $additionData
     * @return JsonResponse
     */
    public static function serverErrorResponse(string|int $error_code, string $file, string|int $line,
                                        string|null $message = null, array $additionData = []): JsonResponse
    {
        return self::response([
            'success' => false,
            'type' => 'error',
            'reason' => 'Exceptions',
            'message' => $message ?? __('response_messages.server_error'),
            'error_code' => $error_code,
            'file' => $file,
            'line' => $line,
            ...$additionData
        ], HttpRequestStatusEnum::STATUS_SERVER_ERROR);
    }

    /**
     * pagination response
     * @param $data
     * @param $meta
     * @param string $reason
     * @return JsonResponse
     */
    public static function successShowPaginationResponse($data, $meta, string $reason = 'Show'): JsonResponse
    {
        return self::successResponse($data, $reason, null, [
            'meta' => $meta,
        ]);
    }

    /**
     * pagination full response
     * @param JsonResource $data
     * @param string $reason
     * @return JsonResponse
     */
    public static function successShowPaginatedDataResponse(JsonResource $data, string $reason = 'Show'): JsonResponse
    {
        return self::successResponse($data, $reason, null, [
            'count' => $data->count(),
        ]);
    }

    /**
     * created successfully response
     * @param $data
     * @param string|null $message
     * @return JsonResponse
     */
    public static function createdSuccessfullyResponse($data = null, string|null $resourceName = null,
                                                ?string $message = null): JsonResponse
    {
        $message = $message ??
            (($resourceName ?? __('response_messages.default_resource_name'))
                . ' ' . __('response_messages.created_successfully'));
        return self::successResponse($data, 'Create',
            $message, [], HttpRequestStatusEnum::STATUS_CREATED);
    }
}