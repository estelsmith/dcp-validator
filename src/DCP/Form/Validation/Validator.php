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

    public function __construct(RuleSetInterface $ruleSet = null)
    {
        if (!$ruleSet) {
            $ruleSet = new RuleSet();
        }

        $this->ruleSet = $ruleSet;
    }

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
    public function addRule(RuleInterface $rule)
    {
        $this->ruleSet->add($rule);
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

        if ($rules) {
            /** @var RuleInterface $rule */
            foreach ($rules as $rule) {
                if ($validationGroup === null || in_array($validationGroup, $rule->getValidationGroups(), true)) {
                    $fieldName = $rule->getFieldName();

                    $data = $this->getFieldData($form, $fieldName);

                    $this->processFilters($form, $rule, $data, $getFieldDataCallback);

                    if (!$this->processPrerequisites($rule, $data, $getFieldDataCallback)) {
                        continue;
                    }

                    $this->processConstraints($result, $rule, $data, $getFieldDataCallback);
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
            if (is_object($form)) {
                $getter = 'get' . ucfirst($fieldName);

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
            if (is_object($form)) {
                $setter = 'set' . ucfirst($fieldName);

                if (!method_exists($form, $setter)) {
                    throw new Exception\DomainException(sprintf('Form method %s does not exist', $setter));
                }

                call_user_func_array(array($form, $setter), array($data));
            } else {
                $form[$fieldName] = $data;
            }
        }
    }

    /**
     * @param RuleInterface $rule
     * @param $data
     * @param $getFieldDataCallback
     * @return bool
     */
    protected function processPrerequisites(RuleInterface $rule, $data, $getFieldDataCallback)
    {
        $returnValue = true;

        foreach ($rule->getPrerequisites() as $prerequisite) {
            $returnValue = call_user_func_array($prerequisite, array($data, $getFieldDataCallback));

            if (!$returnValue) {
                break;
            }
        }

        return (bool)$returnValue;
    }

    /**
     * @param $form
     * @param RuleInterface $rule
     * @param $data
     * @param $getFieldDataCallback
     */
    protected function processFilters(&$form, RuleInterface $rule, $data, $getFieldDataCallback)
    {
        foreach ($rule->getFilters() as $filter) {
            $data = call_user_func_array($filter, array($data, $getFieldDataCallback));
        }

        $this->setFieldData($form, $rule->getFieldName(), $data);
    }

    /**
     * @param ResultInterface $result
     * @param RuleInterface $rule
     * @param $data
     * @param $getFieldDataCallback
     */
    protected function processConstraints(ResultInterface $result, RuleInterface $rule, $data, $getFieldDataCallback)
    {
        foreach ($rule->getConstraints() as $constraint) {
            $constraintResult = call_user_func_array($constraint, array($data, $getFieldDataCallback));

            if ($constraintResult === false) {
                $result->addError($rule->getMessage(), $rule->getFieldName());
                break;
            }
        }
    }
}
