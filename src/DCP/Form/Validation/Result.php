<?php
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
    public function isValid()
    {
        return count($this->errors) == 0;
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
