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
interface RuleInterface
{
    /**
     * @return mixed
     */
    public function getFilters();

    /**
     * @param callable $filter
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function addFilter($filter);

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @param string $fieldName
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function setFieldName($fieldName);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function setMessage($message);

    /**
     * @return mixed
     */
    public function getConstraints();

    /**
     * @param callable $constraint
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function addConstraint($constraint);

    /**
     * @return mixed
     */
    public function getValidationGroups();

    /**
     * @param string $validationGroup
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function addValidationGroup($validationGroup);
}
