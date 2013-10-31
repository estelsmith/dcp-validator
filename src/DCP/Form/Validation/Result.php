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
 * A base implementation of the form validation results.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Result implements ResultInterface
{
    /**
     * @var mixed
     */
    protected $errors = array();

    /**
     * {@inheritdoc}
     */
    public function addError($error, $field = '_form')
    {
        if (!is_string($error)) {
            throw new Exception\InvalidArgumentException('error must be a string');
        }

        if (!is_string($field)) {
            throw new Exception\InvalidArgumentException('field must be a string');
        }

        $this->errors[$field] = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getError($field = '_form')
    {
        if (!is_string($field)) {
            throw new Exception\InvalidArgumentException('field must be a string');
        }

        return $this->fieldHasError($field) ? $this->errors[$field] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function fieldHasError($field)
    {
        if (!is_string($field)) {
            throw new Exception\InvalidArgumentException('field must be a string');
        }

        return isset($this->errors[$field]);
    }
}