<?php

namespace DCP\Form\Validation;

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