<?php
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation;

/**
 * Provides a collection of validation rules to be used by the form validator.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class RuleSet implements RuleSetInterface
{
    protected $position = 0;
    protected $data = array();

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->data[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function add(RuleInterface $rule)
    {
        $this->data[] = $rule;

        return $this;
    }
}
