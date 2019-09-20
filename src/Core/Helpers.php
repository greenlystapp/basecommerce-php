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
    public static function validateArray($array, $keys)
    {
        foreach ($keys as $key) {
            if (array_has($array, $key) == false) {
                throw LogicException::requiredFieldDoesntExist($key);
            }
        }
    }


    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function array_except($array, $keys)
    {
        return array_diff_key($array, array_flip((array)$keys));
    }

    public static function replaceArrayKeys($array, $search, $replace)
    {
        $newArray = [];

        foreach ($array as $key => $item) {
            $newArray[str_replace($search, $replace, $key)] = $item;
        }

        return $newArray;
    }

}