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
     */
    public function setRuleSet(RuleSetInterface $ruleSet);

    /**
     * Retrieve the form that is used for validation.
     *
     * @return mixed
     */
    public function getForm();

    /**
     * Set the form to be used for validation.
     *
     * @param mixed $form
     * @throws Exception\InvalidArgumentException
     */
    public function setForm($form);

    /**
     * Validate the form and return results of the validation.
     *
     * @param string $validationGroup
     * @return ResultInterface
     * @throws Exception\InvalidArgumentException
     */
    public function validate($validationGroup = null);
}
