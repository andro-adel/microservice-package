<?php

namespace DD\MicroserviceCore\Abstracts;

use DD\MicroserviceCore\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A form request that that throws an API validation exception of failure.
 * This is a parent class that should be extended by form requests that handle Api requests.
 */
abstract class ApiFormRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     * @throws CustomValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw (new CustomValidationException($validator));
    }

    /**
     * Global validation messages
     *
     * @return array
     */
    public function messages(): array
    {
       return [];
    }
}
