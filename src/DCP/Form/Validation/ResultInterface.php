<?php
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation;

/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
interface ResultInterface
{
    /**
     * Add a form error for a specified field.
     *
     * @param string $error
     * @param string $field
     * @throws Exception\InvalidArgumentException
     */
    public function addError($error, $field = '_form');

    /**
     * Return TRUE if the form contains errors, FALSE otherwise.
     *
     * @return bool
     */
    public function hasErrors();

    /**
     * Returns an array of all form errors.
     *
     * @return mixed
     */
    public function getErrors();

    /**
     * Retrieve error message for the given field, if one exists.
     *
     * @param string $field
     * @return string|bool Returns error message if it exists, FALSE otherwise.
     * @throws Exception\InvalidArgumentException
     */
    public function getError($field = '_form');

    /**
     * Return TRUE if a given form field has an error, FALSE otherwise.
     *
     * @param string $field
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    public function fieldHasError($field);
}
