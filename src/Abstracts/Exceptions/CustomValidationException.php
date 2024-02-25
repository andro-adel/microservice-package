<?php

namespace DD\MicroserviceCore\Abstracts\Exceptions;

use DD\MicroserviceCore\Classes\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CustomValidationException extends ValidationException
{

    /**
     */
    public function render(): JsonResponse
    {
        return ApiResponses::notValidResponse(
            $this->validator->errors()->toArray(),
        );
    }
}
