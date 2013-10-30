<?php
/* Copyright (c) 2013 Estel Smith
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation;

/**
 * Provides basic form field validation callbacks.
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Constraints
{
    public static function formatEmail()
    {
        return function ($data) {
            $returnValue = FALSE;

            if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                $returnValue = TRUE;
            }

            return $returnValue;
        };
    }

    public static function formatDigits()
    {
        return function ($data) {
            $returnValue = FALSE;

            if ((is_string($data) || is_numeric($data)) && preg_match('/^[0-9]+$/', $data)) {
                $returnValue = TRUE;
            }

            return $returnValue;
        };
    }

    public static function formatNumeric()
    {
        return function ($data) {
            return is_numeric($data);
        };
    }

    public static function formatRegex($regex)
    {
        return function ($data) use($regex) {
            return (bool)preg_match($regex, $data);
        };
    }

    public static function requiredBlank()
    {
        return function ($data) {
            return empty($data);
        };
    }

    public static function requiredValue($value)
    {
        return function ($data) use($value) {
            return $data == $value;
        };
    }
}