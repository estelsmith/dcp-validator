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
 * Provides an interface for creating form validator objects.
 * @package DCP
 * @subpackage Form
 * @author Estel Smith <estel.smith@gmail.com>
 */
interface FormValidatorInterface
{
	/**
	* Retrieve the form object that the validator is tied to.
	* @return mixed
	*/
	public function getForm();

	/**
	 * Returns the validation rules that are currently utilized by the form.
	 * @return array
	 */
	public function getValidationRules();

	/**
	 * Sets the validation rules to be used by the form.
	 * @param array $rules
	 */
	public function setValidationRules($rules);

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
	public function validate();

}