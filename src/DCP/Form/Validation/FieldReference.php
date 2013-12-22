<?php
/**
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Form\Validation;

/**
 * Provides a mechanism for allowing constraints/filters to reference fields at validation time.
 *
 * @package dcp-validator
 * @author Estel Smith <estel.smith@gmail.com>
 */
class FieldReference
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @param $field
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($field)
    {
        if (!is_string($field)) {
            throw new Exception\InvalidArgumentException('field must be a string');
        }

        $this->field = $field;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->field;
    }
}
