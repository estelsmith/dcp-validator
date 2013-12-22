<?php
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

        // Wrap getFieldData in a closure to expose the method to outside uses, since it's protected.
        $getFieldDataCallback = function ($field) {
            return $this->getFieldData($field);
        };

        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            $fieldName = $rule->getFieldName();

            $data = $this->getFieldData($fieldName);

            foreach ($rule->getFilters() as $filter) {
                $data = call_user_func_array($filter, array($data, $getFieldDataCallback));
            }

            $this->setFieldData($fieldName, $data);

            foreach ($rule->getConstraints() as $constraint) {
                $constraintResult = call_user_func_array($constraint, array($data, $getFieldDataCallback));

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
