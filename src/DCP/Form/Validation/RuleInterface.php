<?php

namespace DCP\Form\Validation;

interface RuleInterface
{
    /**
     * @return mixed
     */
    public function getBeforeValidate();

    /**
     * @param callable $beforeValidate
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function addBeforeValidate($beforeValidate);

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
     * @return boolean
     */
    public function getRequired();

    /**
     * @param bool $required
     * @return RuleInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setRequired($required = true);

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