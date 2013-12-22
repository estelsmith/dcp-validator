<?php

namespace spec\Stubs\Validator;

class TestFormMissingSetter
{
    protected $testField;

    protected $anotherField;

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
