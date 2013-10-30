<?php

namespace DCP\Form\Validation;

interface RuleSetInterface extends \Iterator
{
    /**
     * @param RuleInterface $rule
     * @return RuleSet
     */
    public function add(RuleInterface $rule);
}