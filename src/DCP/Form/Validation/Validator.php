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
 * Validates forms based off a set of defined processing rules.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Validator implements ValidatorInterface
{
    /**
     * @var RuleSetInterface
     */
    protected $ruleSet;
    /**
     * @var mixed
     */
    protected $form;

    /**
     * {@inheritdoc}
     */
    public function getRuleSet()
    {
        return $this->ruleSet;
    }

    /**
     * {@inheritdoc}
     */
    public function setRuleSet(RuleSetInterface $ruleSet)
    {
        $this->ruleSet = $ruleSet;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setForm($form)
    {
        if (!is_array($form) && !is_object($form)) {
            throw new Exception\InvalidArgumentException('form must be an array or object');
        }

        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $result = new Result();
        $rules = $this->getRuleSet();

        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            $fieldName = $rule->getFieldName();

            $data = $this->getFieldData($fieldName);

            foreach ($rule->getFilters() as $filter) {
                $data = call_user_func_array($filter, array($data));
            }

            $this->setFieldData($fieldName, $data);

            foreach ($rule->getConstraints() as $constraint) {
                $constraintResult = call_user_func_array($constraint, array($data));

                if ($constraintResult === false) {
                    $result->addError($rule->getMessage(), $fieldName);
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $fieldName
     * @return mixed|null
     * @throws Exception\DomainException
     */
    protected function getFieldData($fieldName)
    {
        $form = $this->getForm();

        $data = null;

        if ($form) {
            $getter = 'get' . ucfirst($fieldName);

            if (is_object($form)) {
                if (!method_exists($form, $getter)) {
                    throw new Exception\DomainException(sprintf('Form method %s does not exist', $getter));
                }

                $data = call_user_func(array($form, $getter));
            } else {
                if (isset($form[$fieldName])) {
                    $data = $form[$fieldName];
                }
            }
        }

        return $data;
    }

    /**
     * @param string $fieldName
     * @param mixed $data
     * @throws Exception\DomainException
     */
    protected function setFieldData($fieldName, $data)
    {
        $form = $this->getForm();

        if ($form) {
            $setter = 'set' . ucfirst($fieldName);

            if (is_object($form)) {
                if (!method_exists($form, $setter)) {
                    throw new Exception\DomainException(sprintf('Form method %s does not exist', $setter));
                }

                call_user_func_array(array($form, $setter), array($data));
            } else {
                $form[$fieldName] = $data;
                $this->setForm($form);
            }
        }
    }
}
