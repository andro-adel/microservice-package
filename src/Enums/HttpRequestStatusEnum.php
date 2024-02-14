<?php

namespace DD\MicroserviceCore\Enums;

enum HttpRequestStatusEnum: int
{
        // Informational (1XX)
    case STATUS_CONTINUE = 100;
        // Success (2XX)
    case STATUS_OK = 200; // OK (List, Update, Delete)
    case STATUS_CREATED = 201; // Created
    case STATUS_SUCCESS_WITH_NO_CONTENT = 204; // Created
        // Redirection (3XX)
    case STATUS_MOVED_PERMANENTLY = 301; // Moved Permanently
    case STATUS_NOT_MODIFIED = 304; // Not Modified (Created, Updated, Deleted)
    case STATUS_HTTP_FOUND = 302; // redirect to new action
        // Client Error (4XX)
    case STATUS_BAD_REQUEST = 400; // Bad Request
    case STATUS_UNAUTHORIZED = 403; // Unauthorized
    case STATUS_PAYMENT_REQUIRED = 402; // Payment Required
    case STATUS_UNAUTHENTICATED = 401; // unauthenticated
    case STATUS_NOT_FOUND = 404; // Not Found
    case STATUS_CONFLICT = 409; // Not Found
    case STATUS_VALIDATIONS_ERROR = 422; // Not Found
    case STATUS_TO_MANY_REQUESTS = 429; // Internal Server Error
        // Server Error (5XX)
    case STATUS_SERVER_ERROR = 500; // Internal Server Error
}
