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
interface ValidatorInterface
{
    /**
     * Retrieve current rule set for the validator.
     *
     * @return RuleSetInterface
     */
    public function getRuleSet();

    /**
     * Set current rule set for the validator.
     *
     * @param RuleSetInterface $ruleSet
     * @return $this
     */
    public function setRuleSet(RuleSetInterface $ruleSet);

    /**
     * Validate the form and return results of the validation.
     *
     * @param mixed $form
     * @param string $validationGroup
     * @return ResultInterface
     * @throws Exception\InvalidArgumentException
     */
    public function validate(&$form, $validationGroup = null);
}
