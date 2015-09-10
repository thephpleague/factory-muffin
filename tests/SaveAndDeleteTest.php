<?php

use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\SaveFailedException;
use League\FactoryMuffin\Exceptions\SaveMethodNotFoundException;
use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * @group savedelete
 */
class SaveAndDeleteTest extends AbstractTestCase
{
    public function testShouldCreateAndDelete()
    {
        $obj = FactoryMuffin::create('ModelThatWillSaveStub');
        $this->assertTrue(FactoryMuffin::isSaved($obj));
        $this->assertTrue(is_numeric($obj->id));
        $this->assertInternalType('array', FactoryMuffin::saved());
        $this->assertCount(1, FactoryMuffin::saved());
        $this->assertCount(0, FactoryMuffin::pending());

        FactoryMuffin::deleteSaved();
    }

    public function testShouldNotSave()
    {
        $obj = FactoryMuffin::instance('ModelThatWillSaveStub');
        $this->assertCount(0, FactoryMuffin::saved());
        $this->assertCount(0, FactoryMuffin::pending());
        $this->assertFalse(FactoryMuffin::isSaved($obj));
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     */
    public function testShouldThrowExceptionAfterSaveMethodRename()
    {
        $return = FactoryMuffin::setSaveMethod('foo');
        $this->assertInstanceOf('League\FactoryMuffin\Factory', $return);
        try {
            FactoryMuffin::create($model = 'ModelThatWillSaveStub');
        } catch (SaveMethodNotFoundException $e) {
            $this->assertSame("The save method 'foo' was not found on the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            $this->assertSame('foo', $e->getMethod());
            $this->assertInstanceOf($model, $e->getObject());
            $this->reload();
            throw $e;
        }

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testShouldThrowExceptionAfterDeleteMethodRename()
    {
        $return = FactoryMuffin::setDeleteMethod('bar');
        $this->assertInstanceOf('League\FactoryMuffin\Factory', $return);
        FactoryMuffin::create($model = 'ModelThatWillSaveStub');
        try {
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame('We encountered 1 problem(s) while trying to delete the saved models.', $e->getMessage());
            $this->assertSame("The delete method 'bar' was not found on the model of type: '$model'.", $exceptions[0]->getMessage());
            $this->assertSame($model, $exceptions[0]->getModel());
            $this->assertSame('bar', $exceptions[0]->getMethod());
            $this->assertInstanceOf($model, $exceptions[0]->getObject());
            $this->reload();
            throw $e;
        }

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveFailedException
     */
    public function testShouldThrowExceptionOnModelSaveFailure()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatFailsToSaveStub');
        } catch (SaveFailedException $e) {
            $this->assertSame("We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            $this->assertNull($e->getErrors());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testShouldThrowExceptionOnModelDeleteFailure()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatFailsToDeleteStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame('We encountered 1 problem(s) while trying to delete the saved models.', $e->getMessage());
            $this->assertSame("We could not delete the model of type: '$model'.", $exceptions[0]->getMessage());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testShouldAlsoThrowExceptionOnModelDeleteFailure()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatAlsoFailsToDeleteStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame('We encountered 1 problem(s) while trying to delete the saved models.', $e->getMessage());
            $this->assertSame('OH NOES!', $exceptions[0]->getMessage());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     */
    public function testShouldThrowExceptionWithoutSaveMethod()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithNoSaveMethodStub');
        } catch (SaveMethodNotFoundException $e) {
            $this->assertSame("The save method 'save' was not found on the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            $this->assertSame('save', $e->getMethod());
            $this->assertInstanceOf($model, $e->getObject());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testShouldThrowExceptionWithoutDeleteMethod()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithNoDeleteMethodStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame('We encountered 1 problem(s) while trying to delete the saved models.', $e->getMessage());
            $this->assertSame("The delete method 'delete' was not found on the model of type: '$model'.", $exceptions[0]->getMessage());
            $this->assertSame($model, $exceptions[0]->getModel());
            $this->assertSame('delete', $exceptions[0]->getMethod());
            $this->assertInstanceOf($model, $exceptions[0]->getObject());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveFailedException
     */
    public function testShouldThrowExceptionWithValidationErrors()
    {
        try {
            FactoryMuffin::create($model = 'ModelWithValidationErrorsStub');
        } catch (SaveFailedException $e) {
            $this->assertSame("Failed to save. We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            $this->assertSame('Failed to save.', $e->getErrors());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testShouldThrowMultipleDeletionExceptions()
    {
        try {
            FactoryMuffin::create($model = 'ModelThatAlsoFailsToDeleteStub');
            FactoryMuffin::create($model = 'ModelWithNoDeleteMethodStub');
            FactoryMuffin::deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame('We encountered 2 problem(s) while trying to delete the saved models.', $e->getMessage());
            $this->assertSame('OH NOES!', $exceptions[0]->getMessage());
            $this->assertSame("The delete method 'delete' was not found on the model of type: '$model'.", $exceptions[1]->getMessage());
            $this->assertSame($model, $exceptions[1]->getModel());
            $this->assertSame('delete', $exceptions[1]->getMethod());
            $this->assertInstanceOf($model, $exceptions[1]->getObject());
            $this->assertInternalType('array', $e->getExceptions());
            $this->assertCount(2, $e->getExceptions());
            throw $e;
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
        return false;
    }
}

class ModelThatAlsoFailsToDeleteStub
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
