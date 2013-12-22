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
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(&$form, $validationGroup = null)
    {
        if ($validationGroup !== null && !is_string($validationGroup)) {
            throw new Exception\InvalidArgumentException('validationGroup must be a string');
        }

        $result = new Result();
        $rules = $this->getRuleSet();

        // Wrap getFieldData in a closure to expose the method to outside uses, since it's protected.
        $getFieldDataCallback = function ($field) use (&$form) {
            return $this->getFieldData($form, $field);
        };

        /** @var RuleInterface $rule */
        if ($rules) {
            foreach ($rules as $rule) {
                if ($validationGroup === null || in_array($validationGroup, $rule->getValidationGroups(), true)) {
                    $fieldName = $rule->getFieldName();

                    $data = $this->getFieldData($form, $fieldName);

                    foreach ($rule->getFilters() as $filter) {
                        $data = call_user_func_array($filter, array($data, $getFieldDataCallback));
                    }

                    $this->setFieldData($form, $fieldName, $data);

                    foreach ($rule->getConstraints() as $constraint) {
                        $constraintResult = call_user_func_array($constraint, array($data, $getFieldDataCallback));

                        if ($constraintResult === false) {
                            $result->addError($rule->getMessage(), $fieldName);
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param mixed $form
     * @param string $fieldName
     * @return mixed|null
     * @throws Exception\DomainException
     */
    protected function getFieldData($form, $fieldName)
    {
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
     * @param mixed $form
     * @param string $fieldName
     * @param mixed $data
     * @throws Exception\DomainException
     */
    protected function setFieldData(&$form, $fieldName, $data)
    {
        if ($form) {
            $setter = 'set' . ucfirst($fieldName);

            if (is_object($form)) {
                if (!method_exists($form, $setter)) {
                    throw new Exception\DomainException(sprintf('Form method %s does not exist', $setter));
                }

                call_user_func_array(array($form, $setter), array($data));
            } else {
                $form[$fieldName] = $data;
            }
        }
    }
}
