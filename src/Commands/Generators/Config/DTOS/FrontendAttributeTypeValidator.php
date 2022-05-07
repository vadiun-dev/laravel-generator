<?php

namespace Src\Client\BusinessClient\Rate\Actions\DTOS\Validators;

use Attribute;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class FrontendAttributeTypeValidator implements Validator
{
    public function validate(mixed $value): ValidationResult
    {
        if (! in_array($value, ['string', 'int', 'float', 'file', 'array', 'bool', 'date']) && ! ctype_upper($value[0])) {
            return ValidationResult::invalid("el tipo $value no puede ser procesado");
        } else {
            return ValidationResult::valid();
        }
    }
}
