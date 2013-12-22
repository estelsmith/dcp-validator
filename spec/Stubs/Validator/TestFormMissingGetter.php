<?php

namespace spec\Stubs\Validator;

class TestFormMissingGetter
{
    protected $testField;

    protected $anotherField;

    public function setTestField($testField)
    {
        $this->testField = $testField;
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
