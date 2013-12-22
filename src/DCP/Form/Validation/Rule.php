<?php
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
     * @var mixed
     */
    protected $validationGroups = array();

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
     * {@inheritdoc}
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function addConstraint($constraint)
    {
        if (!is_callable($constraint)) {
            throw new Exception\InvalidArgumentException('constraint must be callable');
        }

        $this->constraints[] = $constraint;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationGroups()
    {
        return $this->validationGroups;
    }

    /**
     * {@inheritdoc}
     */
    public function addValidationGroup($validationGroup)
    {
        if (!is_string($validationGroup)) {
            throw new Exception\InvalidArgumentException('validationGroup must be a string');
        }

        $this->validationGroups[] = $validationGroup;

        return $this;
    }
}
