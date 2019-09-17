<?php

namespace Greenlyst\BaseCommerce;

use Exception;

class LogicException extends Exception
{
    public static function noCustomFieldPrefixDefined(): self
    {
        return new static('The prefix for the Custom Field is not mentioned on the class');
    }

    public static function only10CustomFieldsAreAllowed(): self
    {
        return new static('Only 10 custom fields are allowed in the class');
    }

    public static function requiredFieldDoesntExist($requiredField): self
    {
        $field = str_replace('_', ' ', $requiredField);

        $field = mb_convert_case($field, MB_CASE_TITLE, 'UTF-8');

        return new static($field . ' is a mandatory field and is not set');
    }
}