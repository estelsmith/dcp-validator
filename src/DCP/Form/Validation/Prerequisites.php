<?php

namespace DCP\Form\Validation;

class Prerequisites
{
    public static function notBlank(FieldReference $reference)
    {
        return function ($data, $formDataCallback) use ($reference) {
            $value = call_user_func_array($formDataCallback, array((string)$reference));

            return strlen($value) > 0;
        };
    }

    public static function isBlank(FieldReference $reference)
    {
        return function ($data, $formDataCallback) use ($reference) {
            $value = call_user_func_array($formDataCallback, array((string)$reference));

            return strlen($value) === 0;
        };
    }

    public static function mustMatch(FieldReference $reference, $constraint)
    {
        return function ($data, $formDataCallback) use ($reference, $constraint) {
            if ($constraint instanceof FieldReference) {
                $constraint = call_user_func_array($formDataCallback, array((string)$constraint));
            }

            $value = call_user_func_array($formDataCallback, array((string)$reference));

            return $value === $constraint;
        };
    }
}
