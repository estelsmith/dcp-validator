<?php

namespace spec\Stubs\Validator;

class TestForm
{
    protected $testField;

    protected $anotherField;

    public function setTestField($testField)
    {
        $this->testField = $testField;
    }

    public function getTestField()
    {
        return $this->testField;
    }

    public function setAnotherField($anotherField)
    {
        $this->anotherField = $anotherField;
    }

    public function getAnotherField()
    {
        return $this->anotherField;
    }
}