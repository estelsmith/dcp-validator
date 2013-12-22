<?php
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation\Exception;

use DCP\Form\Validation\Exception;

/**
 * A dcp-validator specific Invalid Argument Exception.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException implements Exception
{
}
