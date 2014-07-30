<?php

use League\FactoryMuffin\Exception\DeleteMethodNotFoundException;
use League\FactoryMuffin\Exception\DeletingFailedException;
use League\FactoryMuffin\Exception\SaveFailedException;
use League\FactoryMuffin\Exception\SaveMethodNotFoundException;
use League\FactoryMuffin\Facade\FactoryMuffin;

/**
 * @group savedelete
 */
class SaveAndDeleteTest extends AbstractTestCase
{
    public function testShouldCreateAndDelete()
    {
        $obj = FactoryMuffin::create('ModelThatWillSaveStub');
        $this->assertTrue(is_numeric($obj->id));
        $this->assertInternalType('array', FactoryMuffin::saved());
        $this->assertCount(1, FactoryMuffin::saved());

        FactoryMuffin::deleteSaved();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testShouldThrowExceptionAfterSaveMethodRename()
    {
        FactoryMuffin::setSaveMethod('foo');
        try {
            FactoryMuffin::create($model = 'ModelThatWillSaveStub');
        } catch (SaveMethodNotFoundException $e) {
            $this->assertEquals("The save method 'foo' was not found on the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('foo', $e->getMethod());
            $this->assertInstanceOf($model, $e->getObject());
        }
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testShouldThrowExceptionAfterDeleteMethodRename()
    {
        FactoryMuffin::setDeleteMethod('bar');
        FactoryMuffin::create($model = 'ModelThatWillSaveStub');
        try {
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertEquals("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertEquals("The delete method 'bar' was not found on the model of type: '$model'.", $exceptions[0]->getMessage());
            $this->assertEquals($model, $exceptions[0]->getModel());
            $this->assertEquals('bar', $exceptions[0]->getMethod());
            $this->assertInstanceOf($model, $exceptions[0]->getObject());
        }
    }

    public function testShouldThrowExceptionOnModelSaveFailure()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatFailsToSaveStub');
        } catch (SaveFailedException $e) {
            $this->assertEquals("We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertNull($e->getErrors());
        }
    }

    public function testShouldThrowExceptionOnModelDeleteFailure()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatFailsToDeleteStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertEquals("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertEquals("OH NOES!", $exceptions[0]->getMessage());
        }
    }

    public function testShouldThrowExceptionWithoutSaveMethod()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithNoSaveMethodStub');
        } catch (SaveMethodNotFoundException $e) {
            $this->assertEquals("The save method 'save' was not found on the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('save', $e->getMethod());
            $this->assertInstanceOf($model, $e->getObject());
        }
    }

    public function testShouldThrowExceptionWithoutDeleteMethod()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithNoDeleteMethodStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertEquals("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertEquals("The delete method 'delete' was not found on the model of type: '$model'.", $exceptions[0]->getMessage());
            $this->assertEquals($model, $exceptions[0]->getModel());
            $this->assertEquals('delete', $exceptions[0]->getMethod());
            $this->assertInstanceOf($model, $exceptions[0]->getObject());
        }
    }

    public function testShouldThrowExceptionWithValidationErrors()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithValidationErrorsStub');
        } catch (SaveFailedException $e) {
            $this->assertEquals("Failed to save. We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('Failed to save.', $e->getErrors());
        }
    }

    public function testShouldThrowMultipleDeletionExceptions()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatFailsToDeleteStub');
            FactoryMuffin::create($model = 'ModelWithNoDeleteMethodStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertEquals("We encountered 2 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertEquals("OH NOES!", $exceptions[0]->getMessage());
            $this->assertEquals("The delete method 'delete' was not found on the model of type: '$model'.", $exceptions[1]->getMessage());
            $this->assertEquals($model, $exceptions[1]->getModel());
            $this->assertEquals('delete', $exceptions[1]->getMethod());
            $this->assertInstanceOf($model, $exceptions[1]->getObject());
            $this->assertInternalType('array', $e->getExceptions());
            $this->assertCount(2, $e->getExceptions());
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

class ModelThatFailsToSaveStub
{
    public function save()
    {
        return false;
    }

    public function delete()
    {
        return true;
    }
}

class ModelThatFailsToDeleteStub
{
    public function save()
    {
        return true;
    }

    public function delete()
    {
        throw new Exception('OH NOES!');
    }
}

class ModelWithNoSaveMethodStub
{
    public function delete()
    {
        return true;
    }
}

class ModelWithNoDeleteMethodStub
{
    public function save()
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
