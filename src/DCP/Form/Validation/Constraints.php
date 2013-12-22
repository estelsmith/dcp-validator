<?php
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation;

/**
 * Provides basic form constraint callbacks.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Constraints
{
    /**
     * Returns a callback constraint indicating that a field must not be blank.
     *
     * @return callable
     */
    public static function notBlank()
    {
        return function ($data) {
            return strlen($data) > 0;
        };
    }

    /**
     * Returns a callback constraint indicating that a field must be formatted like an email address.
     *
     * @return callable
     */
    public static function formatEmail()
    {
        return function ($data) {
            if (strlen($data) > 0) {
                $returnValue = false;

                if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    $returnValue = true;
                }

                return $returnValue;
            }
        };
    }

    /**
     * Returns a callback constraint indicating that a field may only contain digits.
     *
     * @return callable
     */
    public static function formatDigits()
    {
        return function ($data) {
            if (strlen($data) > 0) {
                $returnValue = false;

                if ((is_string($data) || is_numeric($data)) && preg_match('/^[0-9]+$/', $data)) {
                    $returnValue = true;
                }

                return $returnValue;
            }
        };
    }

    /**
     * Returns a callback constraint indicating that a field may only be numeric.
     *
     * @return callable
     */
    public static function formatNumeric()
    {
        return function ($data) {
            if (strlen($data) > 0) {
                return is_numeric($data);
            }
        };
    }

    /**
     * Returns a callback constraint indicating that a field may only contain data that adheres to the
     * provided regular expression.
     *
     * @param string $regex
     * @return callable
     */
    public static function formatRegex($regex)
    {
        return function ($data) use ($regex) {
            if (strlen($data) > 0) {
                return (bool)preg_match($regex, $data);
            }
        };
    }

    /**
     * Returns a callback constraint indicating that a field must not contain any data.
     *
     * @return callable
     */
    public static function isBlank()
    {
        return function ($data) {
            return strlen($data) === 0;
        };
    }

    /**
     * Returns a callback constraint indicating that a field must contain the provided value.
     *
     * @param mixed $value
     * @return callable
     */
    public static function matchesValue($value)
    {
        return function ($data, $formDataCallback) use ($value) {
            if ($value instanceof FieldReference) {
                $value = call_user_func_array($formDataCallback, array((string)$value));
            }

            if (strlen($data) > 0) {
                return $data === $value;
            }
        };
    }
}
