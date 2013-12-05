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
 * Provides a validation rule definition that dictates how the validator should handle form input.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Rule implements RuleInterface
{
    /**
     * @var string
     */
    protected $fieldName = '';

    /**
     * @var string
     */
    protected $message = 'This field failed validation';

    /**
     * @var mixed
     */
    protected $filters = array();

    /**
     * @var mixed
     */
    protected $constraints = array();

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter($filter)
    {
        if (!is_callable($filter)) {
            throw new Exception\InvalidArgumentException('beforeValidate must be callable');
        }

        $this->filters[] = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFieldName($fieldName)
    {
        if (!is_string($fieldName)) {
            throw new Exception\InvalidArgumentException('fieldName must be a string');
        }

        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        if (!is_string($message)) {
            throw new Exception\InvalidArgumentException('message must be a string');
        }

        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param callable $constraint
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function addConstraint($constraint)
    {
        if (!is_callable($constraint)) {
            throw new Exception\InvalidArgumentException('constraint must be callable');
        }

        $this->constraints[] = $constraint;

        return $this;
    }
}
