About
=====
The dcp-validator package provides simple form validation functionality.

Example
-------
	use DCP\Form\Validator\FormValidator;
	use DCP\Form\Validator\FormValidatorResult;
	use DCP\Form\Field\FormFieldTransformations;
	use DCP\Form\Field\FormFieldValidators;
	
	$form = array(); //Create form
	
	$validation_rules = array(
		array(
			'field_name' => 'username',
			'required' => TRUE,
			'message' => 'Username is required',
			'onbeforevalidate' => FormFieldTransformations::trim()
		),
		array(
			'field_name' => 'password',
			'required' => TRUE,
			'message' => 'Password is required'
		)
	);
	
	$validator = new FormValidator($form, new FormValidatorResult());
	$validator->setValidationRules($validation_rules);
	
	$result = $validator->validate();
	
	if (!$result->hasErrors()) {
		echo 'The form passed validation';
	}