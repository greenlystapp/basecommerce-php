<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Core;

use Greenlyst\BaseCommerce\LogicException;
use function ArrayHelpers\array_has;

class Helpers
{
    /**
     * @param $data
     * @return array
     */
    public static function clearArray($data)
    {
        return array_filter($data, function ($item) {
            if ($item === null)
                return false;
            return true;
        });
    }

    /**
     * @param $array
     * @param $keys
     * @throws LogicException
     */
    public static function validate_array($array, $keys)
    {
        foreach ($keys as $key) {
            if (array_has($array, $key) == false) {
                throw LogicException::requiredFieldDoesntExist($key);
            }
        }
    }
}