<?php

namespace DCP\Form\Validation;

class Rule implements RuleInterface
{
    /**
     * @var string
     */
    protected $fieldName = '';

    /**
     * @var boolean
     */
    protected $required = false;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var mixed
     */
    protected $beforeValidate = array();

    /**
     * @var mixed
     */
    protected $constraints = array();

    /**
     * {@inheritdoc}
     */
    public function getBeforeValidate()
    {
        return $this->beforeValidate;
    }

    /**
     * {@inheritdoc}
     */
    public function addBeforeValidate($beforeValidate)
    {
        if (!is_callable($beforeValidate)) {
            throw new Exception\InvalidArgumentException('beforeValidate must be callable');
        }

        $this->beforeValidate[] = $beforeValidate;

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
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequired($required = true)
    {
        if (!is_bool($required)) {
            throw new Exception\InvalidArgumentException('message must be a boolean');
        }

        $this->required = $required;

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