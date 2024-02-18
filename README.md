<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Package

This is a laravel package to be used with each microservice to setup main packages and configurations.

## Packages that will be installed

- [itspire/monolog-loki ^2.1](https://github.com/itspire/monolog-loki).
- [monolog/monolog ^3.5](https://github.com/Seldaek/monolog).
- [open-telemetry/opentelemetry-logger-monolog ^1.0](https://github.com/opentelemetry-php/contrib-logger-monolog).
- [predis/predis ^2.2](https://github.com/predis/predis).
- [vladimir-yuldashev/laravel-queue-rabbitmq ^13.3](https://github.com/vyuldashev/laravel-queue-rabbitmq) - [notion-documentation] (https://malleable-corn-f1b.notion.site/RabbitMQ-Installation-f74ae9ffdded481281e42d6e674fa516).
- [knuckleswtf/scribe ^4.29](https://scribe.knuckles.wtf/laravel/).
- [maatwebsite/excel ^3.1](https://docs.laravel-excel.com/3.1/getting-started/).
- [barryvdh/laravel-snappy ^1.0](https://github.com/barryvdh/laravel-snappy).
- [h4cc/wkhtmltopdf-amd64 ^0.12.x](https://github.com/h4cc/wkhtmltopdf-amd64).
- [pestphp/pest ^2.33](https://pestphp.com/docs/installation).
- [phpunit/phpunit ^10.5](https://github.com/sebastianbergmann/phpunit).

## Installation

You can install the package via composer:

    composer require andro-adel/microservice-package -W

## Configuration

This package will automatically publish the configuration files in the config folder upon installation, extending the project configuration from ddconfig.
and this files are:

- ddconfig.php - This file will be used to configure the logging.channels and database.redis that will be used in the microservice.
- excel.php - This file will be used to configure the exporting and importing of excel files.
- scribe.php - This file will be used to configure the API documentation.
- services-communication.php - This file will be used to configure the services communication.
- snappy.php - This file will be used to configure the downloading and streaming of PDF files.

## Language

This package will automatically publish the language folder in the directory folder containing translations into Arabic and English.

## Testing

This package will automatically publish the tests folder in the directory folder containing the tests for the Pest package. and configure the phpunit.xml file to use the Pest package.

## Usage

### ApiResponses

This functions will be used by the microservice to return the response in a standard way.

```php
use DD\MicroserviceCore\Classes\ApiResponses;
```

1. **success Response**

This function will be used to return a success response with the data, reason, message, additionData, status.

```php
   ApiResponses::successResponse(array $data, string $reason, string|null $message = null, array $additionData = [], $status = 200)
```

```php
    example:
        return ApiResponses::successResponse(data: ["user" : $user], reason: "User retrieved successfully", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 200,
  "success": true,
  "type": "success",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "John@gmail.com"
    }
  },
  "reason": "User retrieved successfully",
  "message": "Done successfully",
  "additionData": {
    "extra": "extra value"
  }
}
```

2. **successNoContent Response**

This function will be used to return a success response with no content.

```php
    ApiResponses::successNoContentResponse()
```

```php
    example:
        return ApiResponses::successNoContentResponse();
```

```json
{
  "status": 204
}
```

3. **notModified Response**

This function will be used to return a not modified response.

```php
    ApiResponses::notModifiedResponse(string|null $resourceName = null,string|null $message = null,array $additionData = [])
```

```php
    example:
         return ApiResponses::notModifiedResponse(resource: "User", message: "User not modified", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 304,
  "success": false,
  "type": "error",
  "reason": "Failure",
  "message": "User not modified",
  "additionData": {
    "extra": "extra value"
  }
}
```

4. **badRequest Response**

This function will be used to return a bad request response.

```php
    ApiResponses::badRequestResponse(string|null $message = null, array $additionData = [], $status = 400)
```

```php
    example:
        return ApiResponses::badRequestResponse(message: "Invalid data", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 400,
  "success": false,
  "type": "error",
  "reason": "Bad Request",
  "message": "Invalid data",
  "additionData": {
    "extra": "extra value"
  }
}
```

5. **unauthorized Response**

This function will be used to return an unauthorized response.

```php
    ApiResponses::unauthorizedResponse(string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::unauthorizedResponse(message: "Unauthorized User", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 403,
  "success": false,
  "type": "error",
  "reason": "Unauthorized",
  "message": "Unauthorized User",
  "additionData": {
    "extra": "extra value"
  }
}
```

5. **unauthenticated Response**

This function will be used to return an unauthenticated response.

```php
    ApiResponses::unauthenticatedResponse(string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::unauthenticatedResponse(message: "Unauthenticated User", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 401,
  "success": false,
  "type": "error",
  "reason": "Unauthenticated",
  "message": "Unauthenticated User",
  "additionData": {
    "extra": "extra value"
  }
}
```

6. **notFound Response**

This function will be used to return a not found response.

```php
    ApiResponses::notFoundResponse(string|null $resourceName = null, string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::notFoundResponse(resource: "User", message: "User not found", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 404,
  "success": false,
  "type": "error",
  "reason": "Not Found",
  "message": "User not found",
  "additionData": {
    "extra": "extra value"
  }
}
```

7. **conflict Response**

This function will be used to return a conflict response.

```php
    ApiResponses::conflictResponse(string $type,array $data,string|null $resourceName = null, string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::conflictResponse(type:"User",data: ["user" : $user], resourceName: "User", message: "User already exists", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 409,
  "success": false,
  "type": "User",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "John@gmail.com"
    }
  },
  "reason": "Failure",
  "message": "User already exists",
  "additionData": {
    "extra": "extra value"
  }
}
```

8. **notValid Response**

This function will be used to return a not valid response.

```php
    ApiResponses::notValidResponse(array $errors, array $data, string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::notValidResponse(errors: ["name" : "Name is required"], data: ["user" : $user], message: "Invalid data", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 422,
  "success": false,
  "type": "error",
  "reason": "Validation",
  "message": "Invalid data",
  "errors": {
    "name": "Name is required"
  },
  "data": {
    "user": {
      "id": 1,
      "email": "john.doe@gmail.com"
    }
  },
  "additionData": {
    "extra": "extra value"
  }
}
```

9. **serverError Response**

This function will be used to return a server error response.

```php
    ApiResponses::serverErrorResponse(string|int  $errorCode,string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::serverErrorResponse(errorCode: 500, message: "Server error", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 500,
  "success": false,
  "type": "error",
  "reason": "Exceptions",
  "message": "Server error",
  "errorCode": 500,
  "additionData": {
    "extra": "extra value"
  }
}
```

10. **successPagination Response**

This function will be used to return a success pagination response.

```php
    ApiResponses::successPaginationResponse(LengthAwarePaginator $data, string $reason = 'Show', string|null $message = null, array $additionData = [])
```

```php
    example:
        return ApiResponses::successPaginationResponse(data: $users, reason: "Users retrieved successfully", additionData: ["extra" : "extra value"]);
```

```json
{
  "status": 200,
  "success": true,
  "type": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@gmail.com"
      },
      {
        "id": 2,
        "name": "Jane Doe",
        "email": "jane.doe@gmail.com"
      },
      {
        "id": 3,
        "name": "Ahmed",
        "email": "ahmed@gmail.com"
      }
    ],
    "first_page_url": "http://localhost:8000/api/users?page=1",
    "from": 1,
    "last_page": 1
  },
  "reason": "Users retrieved successfully",
  "message": "Done successfully",
  "additionData": {
    "extra": "extra value"
  }
}
```

11. **successShowPagination Response**

This function will be used to return a success show pagination response.

```php
    ApiResponses::successShowPaginationResponse($data, $meta, string $reason = 'Show')
```

```php
    example:
        return ApiResponses::successShowPaginationResponse(data: $users, meta: $meta, reason: "Users retrieved successfully");
```

```json
{
  "status": 200,
  "success": true,
  "type": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@gmail.com",
        "created_at": "2022-01-01T00:00:00.000000Z",
        "updated_at": "2022-01-01T00:00:00.000000Z"
      },
      {
        "id": 2,
        "name": "ahmed Doe",
        "email": "ahmed.doe@gmail.com",
        "created_at": "2022-01-01T00:00:00.000000Z",
        "updated_at": "2022-01-01T00:00:00.000000Z"
      }
    ],
    "first_page_url": "http://localhost:8000/api/users?page=1",
    "from": 1,
    "last_page": 1
  },
  "reason": "Users retrieved successfully",
  [
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": 1,
      "path": "http://localhost:8000/api/users",
      "per_page": 15,
      "to": 2,
      "total": 2
    }
  ]
}
```

12. **successShowPaginatedData Response**

This function will be used to return a success show paginated data response.

```php
    ApiResponses::successShowPaginatedDataResponse(JsonResource $data, string $reason = 'Show')
```

```php
    example:
       return ApiResponses::successShowPaginatedDataResponse(data: $users, reason: "Users retrieved successfully");
```

```json
    {
    "status": 200,
    "success": true,
    "type": "success",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@gmail.com",
        "created_at": "2022-01-01T00:00:00.000000Z",
        "updated_at": "2022-01-01T00:00:00.000000Z"
    },
    "reason": "Users retrieved successfully",
  [
    "count": 1
  ]
    }
```

13. **createdSuccessfully Response**

This function will be used to return a created successfully response.

```php
    ApiResponses::createdSuccessfullyResponse($data = null, string|null $resourceName = null,?string $message = null)
```

```php
    example:
        return ApiResponses::createdSuccessfullyResponse(data: ["user" : $user], resourceName: "User", message: "User created successfully");
```

```json
{
  "status": 201,
  "success": true,
  "type": "success",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@gmail.com",
      "created_at": "2022-01-01T00:00:00.000000Z",
      "updated_at": "2022-01-01T00:00:00.000000Z"
    }
  },
  "reason": "Created",
  "message": "User created successfully"
}
```
