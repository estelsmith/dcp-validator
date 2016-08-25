<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\Constraints;
use DCP\Form\Validation\FieldReference;
use DCP\Form\Validation\Filters;
use DCP\Form\Validation\Prerequisites;
use DCP\Form\Validation\Rule;
use DCP\Form\Validation\RuleSet;
use DCP\Form\Validation\Validator;
use DCP\Form\Validation\Exception;
use Tests\Stubs\Validator\TestForm;
use Tests\Stubs\Validator\TestFormMissingGetter;
use Tests\Stubs\Validator\TestFormMissingSetter;

require __DIR__ . '/../../../Stubs/Validator/TestForm.php';
require __DIR__ . '/../../../Stubs/Validator/TestFormMissingSetter.php';
require __DIR__ . '/../../../Stubs/Validator/TestFormMissingGetter.php';

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectImplementsProperInterface()
    {
        $instance = new Validator();

        $this->assertInstanceOf('DCP\Form\Validation\ValidatorInterface', $instance);
    }

    public function testCanSetAndGetRuleSet()
    {
        $expectedResult = $this->getMock('DCP\Form\Validation\RuleSetInterface');
        $actualResult = null;

        $instance = new Validator();
        $instance->setRuleSet($expectedResult);
        $actualResult = $instance->getRuleSet();

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testValidateReturnsResultObjectWhenRuleSetIsEmpty()
    {
        $instance = new Validator();

        $form = [];

        $result = $instance->validate($form);

        $this->assertInstanceOf('DCP\Form\Validation\ResultInterface', $result);
    }

    public function testValidateAppliesFiltersFromRuleSetWhenFormIsAnArray()
    {
        $formBefore = [
            'test_field' => 'test_value',
            'another_field' => 'ANOTHER_VALUE'
        ];

        $formAfter = [
            'test_field' => 'TEST_VALUE',
            'another_field' => 'another_value'
        ];

        // @TODO: make this not reliant on the actual behavior of other classes.
        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('test_field')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('another_field')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $instance->validate($formBefore);

        $this->assertEquals($formAfter, $formBefore);
    }

    public function testValidateThrowsExceptionWhenApplyingFiltersAndFieldSetterIsMissing()
    {
        $form = new TestFormMissingSetter();
        $form->setAnotherField('ANOTHER_VALUE');

        // @TODO: make this not reliant on the actual behavior of other classes.
        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $gotException = false;
        $expectedMessage = 'Form method setTestField does not exist';
        $actualMessage = null;

        try {
            $instance->validate($form);
        } catch (Exception\DomainException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testValidateAppliesFiltersFromRuleSetWhenFormIsAnObject()
    {
        $form = new TestForm();
        $form->setTestField('test_value');
        $form->setAnotherField('ANOTHER_VALUE');

        // @TODO: make this not reliant on the actual behavior of other classes.
        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $instance->validate($form);

        $this->assertEquals('TEST_VALUE', $form->getTestField());
        $this->assertEquals('another_value', $form->getAnotherField());
    }

    public function testValidateTestsConstraintsFromRuleSetWhenFormIsAnArray()
    {
        $form = [
            'test_field' => 'test_value',
            'another_field' => 'ANOTHER_VALUE',
            'reference_field' => 'this is a referenced field',
            'good_reference' => 'this is a referenced field',
            'bad_reference' => 'this is wrong...'
        ];

        // @TODO: make this not reliant on the actual behavior of other classes.
        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('test_field')
                    ->setMessage('test_error')
                    ->addConstraint(Constraints::formatDigits())
            )
            ->add(
                (new Rule())
                    ->setFieldName('another_field')
                    ->setMessage('another_error')
                    ->addConstraint(Constraints::isBlank())
            )
            ->add(
                (new Rule())
                    ->setFieldName('good_reference')
                    ->setMessage('good_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('reference_field')))
            )
            ->add(
                (new Rule())
                    ->setFieldName('bad_reference')
                    ->setMessage('bad_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('reference_field')))
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $result = $instance->validate($form);

        $this->assertEquals('test_error', $result->getError('test_field'));
        $this->assertEquals('another_error', $result->getError('another_field'));
        $this->assertFalse($result->getError('good_reference'));
        $this->assertEquals('bad_reference_error', $result->getError('bad_reference'));
    }

    public function testValidateThrowsExceptionWhenFieldGetterIsMissing()
    {
        $form = new TestFormMissingGetter();
        $form->setAnotherField('ANOTHER_VALUE');

        // @TODO: make this not reliant on the actual behavior of other classes.
        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $gotException = false;
        $expectedMessage = 'Form method getTestField does not exist';
        $actualMessage = null;

        try {
            $instance->validate($form);
        } catch (Exception\DomainException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testValidateTestsConstraintsFromRuleSetWhenFormIsAnObject()
    {
        $form = new TestForm();
        $form
            ->setTestField('test_value')
            ->setAnotherField('ANOTHER_VALUE')
            ->setReferenceField('this is a reference field')
            ->setGoodReference($form->getReferenceField())
            ->setBadReference('this is wrong...')
        ;

        // @TODO: make this not reliant on the actual behavior of other classes.
        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->setMessage('test_error')
                    ->addConstraint(Constraints::formatDigits())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->setMessage('another_error')
                    ->addConstraint(Constraints::isBlank())
            )
            ->add(
                (new Rule())
                    ->setFieldName('goodReference')
                    ->setMessage('good_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('referenceField')))
            )
            ->add(
                (new Rule())
                    ->setFieldName('badReference')
                    ->setMessage('bad_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('referenceField')))
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $result = $instance->validate($form);

        $this->assertEquals('test_error', $result->getError('testField'));
        $this->assertEquals('another_error', $result->getError('anotherField'));
        $this->assertFalse($result->getError('goodReference'));
        $this->assertEquals('bad_reference_error', $result->getError('badReference'));
    }

    public function testValidateThrowsExceptionWhenValidationGroupArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'validationGroup must be a string';
        $actualMessage = null;

        try {
            $instance = new Validator();
            $form = [];
            $instance->validate($form, ['not_a_string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testValidateWillValidateRequestedValidationGroup()
    {
        $form = [
            'page_1_field_1' => 'page 1 test data',
            'page_1_field_2' => '',
            'page_2_field_1' => 'page 2 test data',
            'page_2_field_2' => ''
        ];

        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('page_1_field_1')
                    ->setMessage('page_1_field_1_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_1')
            )
            ->add(
                (new Rule())
                    ->setFieldName('page_1_field_2')
                    ->setMessage('page_1_field_2_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_1')
            )
            ->add(
                (new Rule())
                    ->setFieldName('page_2_field_1')
                    ->setMessage('page_2_field_1_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_2')
            )
            ->add(
                (new Rule())
                    ->setFieldName('page_2_field_2')
                    ->setMessage('page_2_field_2_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_2')
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $result = $instance->validate($form, 'page_1');

        $this->assertFalse($result->getError('page_1_field_1'));
        $this->assertEquals('page_1_field_2_error', $result->getError('page_1_field_2'));
        $this->assertFalse($result->getError('page_2_field_1'));
        $this->assertFalse($result->getError('page_2_field_2'));

        $result = $instance->validate($form, 'page_2');

        $this->assertFalse($result->getError('page_1_field_1'));
        $this->assertFalse($result->getError('page_1_field_2'));
        $this->assertFalse($result->getError('page_2_field_1'));
        $this->assertEquals('page_2_field_2_error', $result->getError('page_2_field_2'));
    }

    public function testAddRuleAddsRuleToExistingRuleSet()
    {
        $expectedRules = [
            $this->getMock('DCP\Form\Validation\RuleInterface'),
            $this->getMock('DCP\Form\Validation\RuleInterface')
        ];

        $instance = new Validator();

        foreach ($expectedRules as $rule) {
            $instance->addRule($rule);
        }

        $actualRules = $instance->getRuleSet();

        foreach ($actualRules as $index => $rule) {
            $this->assertSame($expectedRules[$index], $rule);
        }
    }

    public function testValidateTestsPrerequisitesFromRuleSetWhenFormIsAnArray()
    {
        $form = [
            'test_field' => '',
            'another_field' => 'test_field must not be blank',
            'yet_another_field' => ''
        ];

        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('another_field')
                    ->setMessage('another_field_error')
                    ->addPrerequisite(Prerequisites::notBlank(new FieldReference('test_field')))
                    ->addConstraint(Constraints::mustMatch('Some crazy random stuff.'))
            )
            ->add(
                (new Rule())
                    ->setFieldName('yet_another_field')
                    ->setMessage('yet_another_field_error')
                    ->addPrerequisite(Prerequisites::notBlank(new FieldReference('another_field')))
                    ->addConstraint(Constraints::notBlank())
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $result = $instance->validate($form);

        $this->assertFalse($result->fieldHasError('another_field'));
        $this->assertEquals('yet_another_field_error', $result->getError('yet_another_field'));
    }

    public function testValidateTestsPrerequisitesFromRuleSetWhenFormIsAnObject()
    {
        $form = new TestForm();
        $form->setTestField('');
        $form->setAnotherField('testField must not be blank');
        $form->setYetAnotherField('');

        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->setMessage('another_field_error')
                    ->addPrerequisite(Prerequisites::notBlank(new FieldReference('testField')))
                    ->addConstraint(Constraints::mustMatch('Some crazy random stuff.'))
            )
            ->add(
                (new Rule())
                    ->setFieldName('yetAnotherField')
                    ->setMessage('yet_another_field_error')
                    ->addPrerequisite(Prerequisites::notBlank(new FieldReference('anotherField')))
                    ->addConstraint(Constraints::notBlank())
            )
        ;

        $instance = new Validator();
        $instance->setRuleSet($ruleSet);

        $result = $instance->validate($form);

        $this->assertFalse($result->fieldHasError('anotherField'));
        $this->assertEquals('yet_another_field_error', $result->getError('yetAnotherField'));
    }
}