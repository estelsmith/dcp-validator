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
namespace DCP\Form\Validator;

/**
 * Encapsulates form validation results.
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class FormValidatorResult implements FormValidatorResultInterface
{

	/**
	 * @var array
	 */
	private $_errors = array();

	/**
	 * Add a form error for a specified field.
	 * @param string $error
	 * @param string $field
	 */
	public function addError($error, $field = '_form')
	{
		$this->_errors[$field] = $error;
	}

	/**
	 * Remove an existing form error for a specified field.
	 * @param string $field
	 */
	public function removeError($field)
	{
		if (isset($this->_errors[$field])) {
			unset($this->_errors[$field]);
		}
	}

	/**
	 * Return TRUE if the form contains errors, FALSE otherwise.
	 * @return bool
	 */
	public function hasErrors()
	{
		$returnValue = FALSE;

		if (count($this->_errors) > 0) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Return TRUE if a given form field has an error, FALSE otherwise.
	 * @param string $field
	 * @return bool
	 */
	public function fieldHasError($field)
	{
		$returnValue = FALSE;

		if (isset($this->_errors[$field])) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Returns an array of all form errors.
	 * @return array 
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * Retrieve error message for the given field, if one exists.
	 * @param string $field
	 * @return string|bool Returns error message if it exists, FALSE otherwise.
	 */
	public function getError($field = '_form')
	{
		$returnValue = FALSE;

		if ($this->fieldHasError($field)) {
			$returnValue = $this->_errors[$field];
		}

		return $returnValue;
	}

	/**
	 * Display error message for the given field, if one exists.
	 * @param string $field
	 * @param string $class
	 */
	public function displayError($field = '_form', $class = 'error')
	{
		if ($this->fieldHasError($field)) {
			?><span class="<?php echo htmlentities($class);?>"><?php echo htmlspecialchars($this->getError($field));?></span><?php
		}
	}
}