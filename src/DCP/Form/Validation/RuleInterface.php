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
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function addFilter($filter);

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @param string $fieldName
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setFieldName($fieldName);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setMessage($message);

    /**
     * @return mixed
     */
    public function getConstraints();

    /**
     * @param callable $constraint
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function addConstraint($constraint);
}
