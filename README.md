About
=====
The dcp-validator package provides simple form validation functionality.

Example
-------
```php
    use DCP\Form\Validation\RuleSet;
    use DCP\Form\Validation\Rule;
    use DCP\Form\Validation\Constraints;
    use DCP\Form\Validation\Filters;
    use DCP\Form\Validation\Validator;

    $rules = new RuleSet();
    $rules
        ->add(
            (new Rule())
            ->setFieldName('username')
            ->setMessage('Username is required')
            ->addFilter(Filters::trim())
            ->addFilter(Filters::toLowerCase())
            ->addConstraint(Constraints::notBlank())
        )
        ->add(
            (new Rule())
            ->setFieldName('username')
            ->setMessage('Username must be an email address')
            ->addConstraint(Constraints::formatEmail())
        )
        ->add(
            (new Rule())
            ->setFieldName('password')
            ->setMessage('Password is required')
            ->addConstraint(Constraints::notBlank())
        )
    ;

    $form = array(
        'username' => 'estel.smith@gmail.com',
        'password' => 'test123'
    );

    $validator = new Validator();
    $validator->setRuleSet($rules);
    $validator->setForm($form);

    $result = $validator->validate();

    if (!$result->hasErrors()) {
        echo 'Hooray! The form passed validation!';
    }
```