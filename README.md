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
        return ApiResponses::successResponse(data: ['user' => $user], reason: 'User retrieved successfully', additionData: ['extra' => 'extra value']);
```

```json
{
    "status": 200,
    "reason": "User retrieved successfully",
    "message": null,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "
        }
    },
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
    "status": 204,
    "reason": "User deleted successfully",
    "message": null,
    "additionData": {
        "extra": "extra value"
    }
}
```
