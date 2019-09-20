<?php

use function ArrayHelpers\array_has;

if (!function_exists('clear_array')) {
    function clear_array($data)
    {
        return array_filter($data, function ($item) {
            if ($item === null) {
                return false;
            }

            return true;
        });
    }
}

if (!function_exists('validate_array')) {
    /**
     * @param $array
     * @param $keys
     *
     * @throws \Greenlyst\BaseCommerce\LogicException
     */
    function validate_array($array, $keys)
    {
        foreach ($keys as $key) {
            if (array_has($array, $key) == false) {
                throw \Greenlyst\BaseCommerce\LogicException::requiredFieldDoesntExist($key);
            }
        }
    }
}
