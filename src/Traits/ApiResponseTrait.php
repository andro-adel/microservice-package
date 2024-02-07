<?php

namespace DD\MicroserviceCore\Traits;

use DD\MicroserviceCore\Enums\HttpRequestStatusEnum;

trait ApiResponseTrait
{
    public function failPermissionMessage($errors)
    {
        return [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_UNAUTHORIZED,
            'reason' => 'Permissions',
            'message' => __('permission_denied'),
            'errors' => $errors,
        ];
    }

    public function failExceptionMessage($error_code, $file, $line, $message)
    {
        $data = [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_SERVER_ERROR->value,
            'reason' => 'Exceptions',
            'message' => $message,
            'error_code' => $error_code,
            'file' => $file,
            'line' => $line,
        ];
        if (is_array($message))
            info(implode(', ', $message));

        return response()->json($data, HttpRequestStatusEnum::STATUS_BAD_REQUEST->value);
    }

    public function failResourceNotFoundMessage($resource_name = null, $message = null)
    {
        $data = [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_NOT_FOUND->value,
            'reason' => 'Record',
            'message' => empty($message) ? (is_null($resource_name)) ? __('Resource_not_found') : $resource_name . ' ' . __('not_found') : $message,
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_NOT_FOUND->value);
    }

    public function successShowDataResponse($data = [], $reason = 'Show', $message = null)
    {
        $data = [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'data' => $data,
            'reason' => $reason,
            'message' => $message ?? __('resource_details'),
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_OK->value);
    }

    public function successShowPaginationResponse($data, $meta, $reason = 'Show')
    {
        $data = [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'data' => $data,
            'meta' => $meta,
            'reason' => $reason,
            'message' => __('resource_details'),
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_OK->value);
    }

    public function successShowPaginatedDataResponse(JsonResource $data, $reason = 'Show')
    {
        $data = [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'data' => $data,
            'count' => $data->count(),
            'reason' => $reason,
            'message' => __('messages.resource_details'),
        ];
        return response()->json($data, HttpRequestStatusEnum::STATUS_OK->value);
    }

    public function successShowMessage($message = null)
    {
        return [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'reason' => 'Show',
            'message' => $message ?? __('resource_details'),
        ];
    }

    public function successUnauthenticatedMessage($message = null)
    {
        return [
            'success' => false,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'reason' => 'Show',
            'message' => $message ?? __('unauthenticated'),
        ];
    }

    public function successListMessage($data, $message = null)
    {
        $body = [
            'success' => true,
            'type' => 'success',
            'reason' => 'List',
            'data' => $data,
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'message' => $message ?? __('resources_listed_successfully'),
        ];

        return response()->json($body, HttpRequestStatusEnum::STATUS_OK->value);
    }

    public function successCreateMessage($data = null, ?string $message = null)
    {
        return [
            'success' => true,
            'type' => 'success',
            'data' => $data,
            'code' => HttpRequestStatusEnum::STATUS_CREATED->value,
            'reason' => 'Create',
            'message' => $message ?? __('resource_created_successfully'),
        ];
    }

    public function failCreateMessage()
    {
        return [
            'success' => false,
            'type' => 'error',
            'code' => $this->status_code_304,
            'reason' => 'Failure',
            'message' => __('resource_not_created_successfully'),
        ];
    }

    public function successUpdateNoContentResponse()
    {
        return response()->json([], HttpRequestStatusEnum::STATUS_SUCCESS_WITH_NO_CONTENT->value);
    }

    public function successUpdateWithContentResponse($data, $message = null)
    {
        $data = [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'reason' => 'Update',
            'data' => $data,
            'message' => $message ?? __('resource_updated_successfully'),
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_OK->value);
    }

    public function failUpdateMessage()
    {
        return [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_NOT_MODIFIED->value,
            'reason' => 'Failure',
            'message' => __('resource_not_updated'),
        ];
    }

    public function failUpdateWithDataMessage($data)
    {
        $response = [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_NOT_MODIFIED->value,
            'reason' => 'Failure',
            'data' => $data,
            'message' => __('resource_not_updated'),
        ];

        return response()->json($response, HttpRequestStatusEnum::STATUS_NOT_MODIFIED->value);
    }

    public function successDeleteMessage($message = null)
    {
        return [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'reason' => 'Delete',
            'message' => $message ?? __('resource_deleted_successfully'),
        ];
    }

    public function failDeleteMessage()
    {
        return [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_NOT_MODIFIED->value,
            'reason' => 'Failure',
            'message' => __('resource_not_deleted'),
        ];
    }

    public function failValidationMessage($errors, $message = null)
    {
        $data = [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_Validation_Error->value,
            'reason' => 'Validation',
            'message' => $message ?? __('inputs_not_valid'),
            'errors' => $errors,
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_Validation_Error->value);
    }

    public function failAuthMessage($message = null)
    {
        $data = [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_UNAUTHORIZED->value,
            'reason' => 'Authentication',
            'message' => $message ?? __('unauthenticated'),
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_UNAUTHORIZED->value);
    }

    public function successGeneralMessage($message = '')
    {
        $data = [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_OK->value,
            'reason' => 'General',
            'message' => $message,
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_OK->value);
    }

    public function failGeneralMessage($message = '')
    {
        $data = [
            'success' => false,
            'type' => 'error',
            'code' => HttpRequestStatusEnum::STATUS_BAD_REQUEST,
            'reason' => 'General',
            'message' => $message,
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_BAD_REQUEST->value);
    }

    public function successًwithActionMessage($message = '')
    {
        $data = [
            'success' => true,
            'type' => 'success',
            'code' => HttpRequestStatusEnum::STATUS_HTTP_FOUND->value,
            'reason' => 'General',
            'message' => $message,
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_HTTP_FOUND->value);
    }

    public function failConflictsMessage(string $type, array $data)
    {
        $data = [
            'success' => false,
            'type' => $type,
            'data' => $data,
            'code' => HttpRequestStatusEnum::STATUS_CONFLICT->value,
            'reason' => 'Failure',
            'message' => __('resource_conflicts'),
        ];

        return response()->json($data, HttpRequestStatusEnum::STATUS_CONFLICT->value);
    }
}
