<?php

use League\FactoryMuffin\Exception\SaveFailed;
use League\FactoryMuffin\Exception\SaveMethodNotFound;
use League\FactoryMuffin\Facade\FactoryMuffin;

/**
 * @group saving
 */
class SavingTest extends AbstractTestCase
{
    public function testShouldCreate()
    {
        $obj = FactoryMuffin::create('ModelThatWillSaveStub');
        $this->assertTrue(is_numeric($obj->id));
    }

    public function testShouldThrowExceptionOnModelSaveFailure()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatFailsToSaveStub');
        } catch (SaveFailed $e) {
            $this->assertEquals("We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertNull($e->getErrors());
        }
    }

    public function testShouldThrowExceptionWithoutSaveMethod()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithNoSaveMethodStub');
        } catch (SaveMethodNotFound $e) {
            $this->assertEquals("The save method 'save' was not found on the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('save', $e->getMethod());
            $this->assertInstanceOf($model, $e->getObject());
        }
    }

    public function testShouldThrowExceptionWithValidationErrors()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithValidationErrorsStub');
        } catch (SaveFailed $e) {
            $this->assertEquals("Failed to save. We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('Failed to save.', $e->getErrors());
        }
    }
}


class ModelThatWillSaveStub
{
    public function save()
    {
        $this->id = date('U');

        return true;
    }

    public function delete()
    {
        return true;
    }
}

class ModelWithValidationErrorsStub
{
    public $validationErrors = 'Failed to save.';

    public function save()
    {

    }

    public function delete()
    {
        return true;
    }
}

class ModelWithNoSaveMethodStub
{
    public function delete()
    {
        return true;
    }
}

class ModelThatFailsToSaveStub
{
    // Eloquent models return False on save failure.
    // We *might* want to throw exceptions in that case.
    public function save()
    {
        return false;
    }

    public function delete()
    {
        return true;
    }
}
