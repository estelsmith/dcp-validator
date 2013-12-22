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
interface RuleSetInterface extends \Iterator
{
    /**
     * @param RuleInterface $rule
     * @return RuleSet
     */
    public function add(RuleInterface $rule);
}
