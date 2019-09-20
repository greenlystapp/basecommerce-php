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
        return new static(self::mapFieldNames($requiredField) . ' is a mandatory field and is not set.');
    }

    public static function pendingImplementation($message): self
    {
        return new static('This section still needs to be implemented for ' . $message);
    }

    public static function methodCallNotSupported($message): self
    {
        return new static('This method call is not supported for' . $message);
    }

    private static function mapFieldNames($field)
    {
        $fieldMappings = [
            'bank_card_name' => 'Name on Credit Card',
            'bank_card_number' => 'Credit Card Number',
            'bank_card_expiration_month' => 'Credit Card Expiry Month',
            'bank_card_expiration_year' => 'Credit Card Expiry Year',
            'bank_card_transaction_name' => 'Name on Credit Card',
            'bank_card_transaction_card_number' => 'Credit Card Number',
            'bank_card_transaction_expiration_month' => 'Credit Card Expiry Month',
            'bank_card_transaction_expiration_year' => 'Credit Card Expiry Year',
            'bank_card_transaction_type' => 'Transaction type',
            'bank_card_transaction_amount' => 'Transaction amount',
            'bank_card_token' => 'Credit Card Token'
        ];

        return $fieldMappings[$field];
    }
}