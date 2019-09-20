<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce;

use Exception;

class ClientException extends Exception
{
    public const INVALID_CREDENTIALS = 'Invalid Credentials';
    public const INVALID_URL_OR_HOST_OFFLINE = 'Invalid URL or Host is offline';
    public const INTERNAL_SERVER_ERROR = 'Internal Server Error. Please contact tech support.';
    public const ERROR_CONNECTING_TO_ENVIRONMENT = 'Error Connecting to BaseCommerce';

    /**
     * @return ClientException
     */
    public static function invalidCredentials(): self
    {
        return new static(self::INVALID_CREDENTIALS, 403);
    }

    /**
     * @return ClientException
     */
    public static function internalServerError(): self
    {
        return new static(self::INTERNAL_SERVER_ERROR, 500);
    }

    /**
     * @return ClientException
     */
    public static function invalidURLOrHost(): self
    {
        return new static(self::INVALID_URL_OR_HOST_OFFLINE, 404);
    }

    /**
     * @return ClientException
     */
    public static function errorConnectingToEnvironment(): self
    {
        return new static(self::ERROR_CONNECTING_TO_ENVIRONMENT, 400);
    }

    public static function unknownError($errorMessage): self
    {
        return new static($errorMessage);
    }
}
