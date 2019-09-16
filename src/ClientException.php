<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce;

use Exception;

class ClientException extends Exception
{
    /**
     * @return ClientException
     */
    public static function invalidCredentials(): self
    {
        return new static('Invalid Credentials', 403);
    }

    /**
     * @return ClientException
     */
    public static function internalServerError(): self
    {
        return new static('Internal Server Error. Please contact tech support.', 500);
    }

    /**
     * @return ClientException
     */
    public static function invalidURLOrHost(): self
    {
        return new static('Invalid URL or Host is offline', 404);
    }

}