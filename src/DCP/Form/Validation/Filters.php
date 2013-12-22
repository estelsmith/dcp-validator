<?php
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation;

/**
 * Provides basic form filter callbacks.
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Filters
{
    /**
     * Returns a callback filter that will trim whitespace from the input.
     *
     * @return callable
     */
    public static function trim()
    {
        return function ($data) {
            return trim($data);
        };
    }

    /**
     * Returns a callback filter that will convert the input to lowercase characters.
     *
     * @return callable
     */
    public static function toLowerCase()
    {
        return function ($data) {
            return strtolower($data);
        };
    }

    /**
     * Returns a callback filter that will convert the input to uppercase characters.
     *
     * @return callable
     */
    public static function toUpperCase()
    {
        return function ($data) {
            return strtoupper($data);
        };
    }
}
