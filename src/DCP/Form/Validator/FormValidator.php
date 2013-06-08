<?php
/* Copyright (c) 2011 Estel Smith
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
 * @package DCP
 * @subpackage Form
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validator;

/**
 * Provides basic form validation functionality.
 * @package DCP
 * @subpackage Form
 * @author Estel Smith <estel.smith@gmail.com>
 */
class FormValidator implements FormValidatorInterface
{

	/**
	 * @var array
	 */
	private $_validationRules = array();

	/**
	 * @var object
	 */
	private $_form;

	/**
	* @var FormValidatorResultInterface
	*/
	private $_resultObject;

	/**
	 * @param object $form 
	 * @param FormValidatorResultInterface $result 
	 */
	public function __construct($form, FormValidatorResultInterface $result)
	{
		if (!is_array($form) && !is_object($form)) {
			throw new \InvalidArgumentException('Form must be an array or object');
		}

		$this->_form = $form;
		$this->_resultObject = $result;
	}

	/**
	* Retrieve the form object that the validator is tied to.
	* @return mixed
	*/
	public function getForm()
	{
		return $this->_form;
	}

	/**
	 * Returns the validation rules that are currently utilized by the form.
	 * @return array
	 */
	public function getValidationRules()
	{
		return $this->_validationRules;
	}

	/**
	 * Sets the validation rules to be used by the form.
	 * @param array $rules
	 */
	public function setValidationRules($rules)
	{
		if (!(is_array($rules) && count($rules) > 0)) {
			throw new \InvalidArgumentException('Rules must be an array containing at least one entry');
		} else {
			$this->_validationRules = $rules;
		}
	}

	/**
	 * Validate a form's input against a given set of rules.
	 * 
	 * Example:
	 * <code>
	 * use DCP\Form\Validator\FormValidator;
	 * use DCP\Form\Validator\FormValidatorResult;
	 * use DCP\Form\Field\FormFieldTransformations;
	 * use DCP\Form\Field\FormFieldValidators;
	 * 
	 * $form = array(); //Create form
	 * 
	 * $validation_rules = array(
	 * 	array(
	 * 		'field_name' => 'username',
	 * 		'required' => TRUE,
	 * 		'message' => 'Username is required',
	 * 		'onbeforevalidate' => FormFieldTransformations::trim()
	 * 	),
	 * 	array(
	 * 		'field_name' => 'password',
	 * 		'required' => TRUE,
	 * 		'message' => 'Password is required'
	 * 	)
	 * );
	 * 
	 * $validator = new FormValidator($form, new FormValidatorResult());
	 * $validator->setValidationRules($validation_rules);
	 * 
	 * $result = $validator->validate();
	 * 
	 * if (!$result->hasErrors()) {
	 * 	echo 'The form passed validation';
	 * }
	 * </code>
	 * @return FormValidatorResultInterface
	 */
	public function validate()
	{
		$validationRules = $this->getValidationRules();

		if (count($validationRules) > 0) {
			foreach ($validationRules as $rule) {
				$fieldHasError = FALSE;

				//Only execute rule if the rule has a defined field name.
				if (isset($rule['field_name']) && strlen($rule['field_name']) > 0) {
					$fieldName = $rule['field_name'];
					$fieldName[0] = strtoupper($fieldName[0]);

					$fieldData = NULL;
					if (is_object($this->_form)) {
						$fieldGetter = 'get' . $fieldName;

						if (!method_exists($this->_form, $fieldGetter)) {
							throw new \DomainException(sprintf('Form getter %s does not exist', $fieldGetter));
						}

						$fieldData = call_user_func(array($this->_form, $fieldGetter));
					} else {
						if (isset($this->_form[$rule['field_name']])) {
							$fieldData = $this->_form[$rule['field_name']];
						}
					}

					//Execute beforevalidate hook if it exists and the field has data.
					if (isset($rule['onbeforevalidate']) && is_callable($rule['onbeforevalidate'])) {
						if ($fieldData) {
							$fieldData = call_user_func_array($rule['onbeforevalidate'], array($fieldData, $rule['field_name']));

							if (is_object($this->_form)) {
								$fieldSetter = 'set' . $fieldName;

								if (!method_exists($this->_form, $fieldSetter)) {
									throw new \DomainException(sprintf('Form setter %s does not exist', $fieldSetter));
								}

								call_user_func_array(array($this->_form, $fieldSetter), array($fieldData));
							} else {
								$this->_form[$rule['field_name']] = $fieldData;
							}
						}
					}

					//Validate required field if rule is set.
					if (isset($rule['required']) && $rule['required']) {
						if (!$fieldData) {
							$fieldHasError = TRUE;
						}
					}

					//Execute onvalidate hook if it exists.
					if (!$fieldHasError) {
						if (isset($rule['onvalidate']) && is_callable($rule['onvalidate'])) {
							$fieldHasError = !(bool)call_user_func_array($rule['onvalidate'], array($fieldData, $rule['field_name']));
						}
					}

					//Set the rule's error message if the field has failed validation.
					if ($fieldHasError) {
						if (isset($rule['message']) && $rule['message']) {
							$this->_resultObject->addError($rule['message'], $rule['field_name']);
						} else {
							$this->_resultObject->addError('Field is required', $rule['field_name']);
						}
					}
				}
			}
		}

		return ($this->_resultObject);
	}

}