# DCP-Validator
DCP-Validator provides a simple API for data and form validation.

Design goals for the project are always to be very concise, simple, extensible, and testable.

[![Build Status](https://travis-ci.org/estelsmith/dcp-validator.png?branch=master)](https://travis-ci.org/estelsmith/dcp-validator)
[![Coverage Status](https://coveralls.io/repos/estelsmith/dcp-validator/badge.png)](https://coveralls.io/r/estelsmith/dcp-validator)

## Getting Started
The first item on the list is installing the package into your application.

The easiest, and recommended, way of doing this is to install the package [through composer](http://getcomposer.org/).

Just create a composer.json file in your project:
```json
{
    "require": {
        "dcp/validator": "1.0.*"
    }
}
```

Then, run the composer command to install DCP-Validator:
```
$ composer install
```

Alternatively, you can clone the repository and install the package manually.

## Basic Usage
### Creating The Validator
The first thing that needs to be done is to create the validator itself.
```php
use DCP\Form\Validation\Validator;

$validator = new Validator();
```

### Adding Rules
Once the validator has been created, you may want to add validation rules so the validator is able to do some work.
```php
use DCP\Form\Validation\Validator;
use DCP\Form\Validation\RuleSet;
use DCP\Form\Validation\Rule;
use DCP\Form\Validation\Constraint;

$ruleSet = (new RuleSet())
    ->add(
        (new Rule())
            ->setFieldName('username')
            ->setMessage('Username cannot be blank')
            ->addConstraint(Constraint::notBlank())
    )
;

$validator = new Validator();
$validator->setRuleSet($ruleSet);
```

Alternatively, you can skip the explicit RuleSet creation, to save typing, and add rules directly through the
validator itself.
```php
use DCP\Form\Validation\Validator;
use DCP\Form\Validation\Rule;
use DCP\Form\Validation\Constraint;

$validator = new Validator();
$validator->addRule(
    (new Rule())
        ->setFieldName('username')
        ->setMessage('Username cannot be blank')
        ->addConstraint(Constraint::notBlank())
);
```

### Validation
Since rules have been added to the validator, it's time to put the validator to work.

To check if a form is valid, just call the validate() method. The validate() method will return a result object, where
you can check if the form was valid, and if not, what errors were thrown.
```php
use DCP\Form\Validation\Validator;
use DCP\Form\Validation\Rule;
use DCP\Form\Validation\Constraint;

$validator = new Validator();
$validator->addRule(
    (new Rule())
        ->setFieldName('username')
        ->setMessage('Username cannot be blank')
        ->addConstraint(Constraint::notBlank())
);

$form = [
    'username' => 'test_user'
];

$result = $validator->validate($form);

if ($result->isValid()) {
    echo 'Hooray, the form is valid!';
}
```

You can also validate data from a class instance:
```php
use DCP\Form\Validation\Validator;
use DCP\Form\Validation\Rule;
use DCP\Form\Validation\Constraint;

class MyForm
{
    protected $username;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}

$validator = new Validator();
$validator->addRule(
    (new Rule())
        ->setFieldName('username')
        ->setMessage('Username cannot be blank')
        ->addConstraint(Constraint::notBlank())
);

$form = new MyForm();
$form->setUsername('test_user');

$result = $validator->validate($form);

if ($result->isValid()) {
    echo 'Hooray, the form is valid!';
}
```

## Detailed Usage
### The Rule Object
The rule object is where the magic happens. Inside here, you can decide what field needs to be validated, constraints
the field should adhere to, as well as what error messages to display when the rule fails validation.

The simplest form of a rule object is just a field name, error message, and some constraint.
```php
(new Rule())
    ->setFieldName('username')
    ->setMessage('Username must not be blank')
    ->addConstraint(Constraints::notBlank())
;
```

Note that setting a message for a rule is not a requirement.
```php
(new Rule())->getMessage();
// This field failed validation
```

Inside the rule object, you are able to set all functionality for a given validation step such as prerequisites,
filters, and constraints.

Full example:
```php
(new Rule())
    ->setFieldName('password_repeat')
    ->setMessage('Passwords must match')
    ->addPrerequisite(Prerequisites::notBlank(new FieldReference('password')))
    ->addFilter(Filters::trim())
    ->addConstraint(Constraints::notBlank())
;
```

### The RuleSet Object
The RuleSet object is nothing more than a collection that holds Rule objects. Its primary purpose is to hold definitions
of validation rules for the validator.
```php
$ruleSet = (new RuleSet())
    ->add(
        (new Rule())
            ->setFieldName('username')
            ->setMessage('Username cannot be blank')
            ->addConstraint(Constraint::notBlank())
    )
;

$validator = new Validator();
$validator->setRuleSet($ruleSet);
```

Keep in mind that the rule set is not required for use of the validator. There is a convenience method on the validator
that allows you to add rules directly to the validator, bypassing the explicit creation of a rule set. *Rule sets are
still used behind the scenes, however.*
```php
$validator = new Validator();
$validator->addRule(
    (new Rule())
        ->setFieldName('username')
        ->setMessage('Username cannot be blank')
        ->addConstraint(Constraint::notBlank())
);
```

### Constraints
Constraints allow you to validate that the data in a field matches your expectations.
```php
(new Rule())
    ->setFieldName('email_address')
    ->addConstraint(Constraints::notBlank())
    ->addConstraint(Constraints::formatEmail())
;
// This will ensure that the email address is not blank, and matches a valid email format (e.g. user@example.com)
```

A few constraints are provided by default:
- Constraints::notBlank()
- Constraints::formatEmail()
- Constraints::formatDigits()
- Constraints::formatNumeric()
- Constraints::formatRegex($regex)
    - Regex format is PCRE, like '/(T|t)est/'
- Constraints::isBlank()
- Constraints::mustMatch($value)
    - Value can either be a literal value, or a field reference

### Filters
Filters are a way to make modifications to data prior to validating a rule. This is typically used for trimming input,
or uppercasing/lowercasing a field.

```php
(new Rule())
    ->setFieldName('username')
    ->addFilter(Filters::trim())
    ->addFilter(Filters::toLowerCase())
;
// This will trim and lowercase the username field prior to validating the rule.
```

A few filters are provided by default:
- Filters::trim()
- Filters::toLowerCase()
- Filters::toUpperCase()

### Prerequisites
Prerequisites allow you to ensure that a rule will only be validated after a set of defined criteria have been met.

A good example of this is to require a field if another field contains a certain value.
```php
/*
 * Assume the form has a field named 'send_me_an_email_newsletter' that is a checkbox with a value
 * of 'yes'. When checked, the user is required to enter their email address to receive email newsletters.
*/

(new RuleSet())
    ->add(
        (new Rule())
            ->setFieldName('email')
            ->setMessage('Email is not valid')
            ->addPrerequisite(Prerequisites::mustMatch(new FieldReference('send_me_an_email_newsletter'), 'yes'))
            ->addFilter(Filters::trim())
            ->addConstraint(Constraints::notBlank())
            ->addConstraint(Constraints::formatEmail())
    )
;
```

Note that Prerequisites::mustMatch() also accepts a field reference as its second argument, so you can check that two
fields match before validating the given rule. It acts much like Constraints::mustMatch() in this regard.

A few prerequisites are provided by default:
- Prerequisites::notBlank(FieldReference $reference)
- Prerequisites::isBlank(FieldReference $reference)
- Prerequisites::mustMatch(FieldReference $reference, $value)
    - Value can either be a literal value, or a field reference

### Field References
Field references allow you to create a reference to the value of another field at validation time. This is especially
useful when you have a form that requires two field values to match.
```php
(new RuleSet())
    ->add(
        (new Rule())
            ->setFieldName('password')
            ->setMessage('Password must not be blank')
            ->addFilter(Filters::trim())
            ->addConstraint(Constraints::notBlank())
    )
    ->add(
        (new Rule())
            ->setFieldName('password_repeat')
            ->setMessage('Passwords do not match')
            // Do not validate unless the password field is not blank.
            ->addPrerequisite(Prerequisites::notBlank(new FieldReference('password')))
            ->addFilter(Filters::trim())
            // This field must match the password field's value.
            ->addConstraint(Constraints::mustMatch(new FieldReference('password')))
    )
;
```

### The Result Object
The result object is returned by the Validator#validate() method. It can be used to determine if form validation was
successful, or if there were any errors processing the form.

The most basic use of the result object is to check and see if validation was a success:
```php
$result = $validator->validate($form);

if ($result->isValid()) {
    echo 'Hooray, the form is valid!';
} else {
    echo 'Better luck next time.';
}
```

More advanced usage of the object includes checking to see if specific fields have errors:
```php
$result = $validator->validate($form);

if (!$result->isValid()) {
    if ($result->fieldHasError('username')) {
        echo 'Username field had a validation error.';
    }
}
```

Or to even retrieve an error message that was thrown during validation. This would be useful for telling the user
what went wrong, and how to fix it:
```php
$result = $validator->validate($form);

if (!$result->isValid()) {
    if ($result->fieldHasError('username')) {
        echo "Username had a validation error. Wonder what it was:\n";
        echo $result->getError('username');
    }
}
```

### Custom Constraints/Filters/Prerequisites
All constraints, filters, and prerequisites are callables. The provided default implementations are closures.
```php
// From the Constraints class
public static function notBlank()
{
    return function ($data) {
        return strlen($data) > 0;
    };
}
```

With this being said, a user can easily build their own validators, filters, or prerequisites by simply defining custom
callbacks.
```php
$steveCallback = function ($data) {
    if ($data === 'steve') {
        return true;
    }

    return false;
};

(new Rule())
    ->setFieldName('username')
    ->setMessage('You are definitely not steve')
    ->addConstraint($steveCallback)
;
```

If a user wants to create reusable callbacks, they can define the callback in a class, and/or extend one of the
appropriate default classes.
```php
class MyConstraints extends Constraints
{
    public static function isSteve()
    {
        return function ($data) {
            if ($data === 'steve') {
                return true;
            }

            return false;
        };
    }
}

(new Rule())
    ->setFieldName('username')
    ->setMessage('You are definitely not steve')
    ->addConstraint(MyConstraints::isSteve())
;
```

Creating custom callbacks works for all callback-based systems such as constraints, filters, and prerequisites.

### Validator Groups
DCP-Validator allows partial form validation by way of validator groups. This is particularly useful when a form spans
across multiple different pages.

Setting a validation group on a rule is done by simply calling `Rule#addValidationGroup`.
```php
(new Rule())
    ->setFieldName('username')
    ->setMessage('Please enter a username')
    ->addConstraint(Constraints::notBlank())
    ->addValidationGroup('page_1')
;
```

When validating a form, you can specify which validation group you wish to validate.
```php
$rules = (new RuleSet())
    ->add(
        (new Rule())
            ->setFieldName('username')
            ->setMessage('Please enter a username')
            ->addConstraint(Constraints::notBlank())
            ->addValidationGroup('page_1')
    )
    ->add(
        (new Rule())
            ->setFieldName('password')
            ->setMessage('Please enter a password')
            ->addConstraint(Constraints::notBlank())
            ->addValidationGroup('page_2')
    )
;

$form = [
    'username' => '',
    'password' => ''
];

$validator = new Validator();
$validator->setRuleSet($rules);

$validator->validate('page_2');
// This will only validate the password field.
```

## Contributing
If you would like to contribute to DCP-Validator, you can do so in one of two ways:
- Submit issues for bugs you find, or functionality that would improve the project.
- Fork the repository, add some functionality, then submit a pull request.

## Testing
DCP-Validator uses PHPUnit 3.7.x for automated testing.

All changes to the codebase are accompanied by unit tests.