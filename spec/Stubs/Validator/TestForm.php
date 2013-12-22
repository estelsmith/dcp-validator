<?php

namespace spec\Stubs\Validator;

class TestForm
{
    protected $testField;

    protected $anotherField;

    protected $referenceField;

    protected $goodReference;

    protected $badReference;

    public function setTestField($testField)
    {
        $this->testField = $testField;
        return $this;
    }

    public function getTestField()
    {
        return $this->testField;
    }

    public function setAnotherField($anotherField)
    {
        $this->anotherField = $anotherField;
        return $this;
    }

    public function getAnotherField()
    {
        return $this->anotherField;
    }

    public function setReferenceField($referenceField)
    {
        $this->referenceField = $referenceField;
        return $this;
    }

    public function getReferenceField()
    {
        return $this->referenceField;
    }

    public function setGoodReference($goodReference)
    {
        $this->goodReference = $goodReference;
        return $this;
    }

    public function getGoodReference()
    {
        return $this->goodReference;
    }

    public function setBadReference($badReference)
    {
        $this->badReference = $badReference;
        return $this;
    }

    public function getBadReference()
    {
        return $this->badReference;
    }
}