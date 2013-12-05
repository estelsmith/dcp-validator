<?php
/* Copyright (c) 2013 Estel Smith
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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
     * @return ResultInterface
     */
    public function validate();
}
