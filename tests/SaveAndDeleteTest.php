<?php

/*
 * This file is part of Factory Muffin.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\SaveFailedException;
use League\FactoryMuffin\Exceptions\SaveMethodNotFoundException;

/**
 * This is save and delete test class.
 *
 * @group savedelete
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class SaveAndDeleteTest extends AbstractTestCase
{
    public function testShouldCreateAndDelete()
    {
        $obj = static::$fm->create('ModelThatWillSaveStub');
        $this->assertTrue(static::$fm->isSaved($obj));
        $this->assertTrue(is_numeric($obj->id));
        $this->assertInternalType('array', static::$fm->saved());
        $this->assertCount(1, static::$fm->saved());
        $this->assertCount(0, static::$fm->pending());

        static::$fm->deleteSaved();
    }

    public function testShouldNotSave()
    {
        $obj = static::$fm->instance('ModelThatWillSaveStub');
        $this->assertCount(0, static::$fm->saved());
        $this->assertCount(0, static::$fm->pending());
        $this->assertFalse(static::$fm->isSaved($obj));
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     */
    public function testShouldThrowExceptionAfterSaveMethodRename()
    {
        $return = static::$fm->setSaveMethod('foo');
        $this->assertInstanceOf('League\FactoryMuffin\FactoryMuffin', $return);
        try {
            static::$fm->create($model = 'ModelThatWillSaveStub');
        } catch (SaveMethodNotFoundException $e) {
            $this->assertSame("The save method 'foo' was not found on the model: '$model'.", $e->getMessage());
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
        $return = static::$fm->setDeleteMethod('bar');
        $this->assertInstanceOf('League\FactoryMuffin\FactoryMuffin', $return);
        static::$fm->create($model = 'ModelThatWillSaveStub');
        try {
            static::$fm->deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertSame("The delete method 'bar' was not found on the model: '$model'.", $exceptions[0]->getMessage());
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
            static::$fm->create($model = 'ModelThatFailsToSaveStub');
        } catch (SaveFailedException $e) {
            $this->assertSame("We could not save the model: '$model'.", $e->getMessage());
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
            static::$fm->create($model = 'ModelThatFailsToDeleteStub');
            static::$fm->deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertSame("We could not delete the model: '$model'.", $exceptions[0]->getMessage());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testShouldAlsoThrowExceptionOnModelDeleteFailure()
    {
        try {
            static::$fm->create($model = 'ModelThatAlsoFailsToDeleteStub');
            static::$fm->deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertSame("OH NOES!", $exceptions[0]->getMessage());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     */
    public function testShouldThrowExceptionWithoutSaveMethod()
    {
        try {
            static::$fm->create($model = 'ModelWithNoSaveMethodStub');
        } catch (SaveMethodNotFoundException $e) {
            $this->assertSame("The save method 'save' was not found on the model: '$model'.", $e->getMessage());
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
            static::$fm->create($model = 'ModelWithNoDeleteMethodStub');
            static::$fm->deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertSame("The delete method 'delete' was not found on the model: '$model'.", $exceptions[0]->getMessage());
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
            static::$fm->create($model = 'ModelWithValidationErrorsStub');
        } catch (SaveFailedException $e) {
            $this->assertSame("Failed to save. We could not save the model: '$model'.", $e->getMessage());
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
            static::$fm->create($model = 'ModelWithNoDeleteMethodStub');
            static::$fm->create('ModelThatAlsoFailsToDeleteStub');
            static::$fm->deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame("We encountered 2 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertSame("OH NOES!", $exceptions[0]->getMessage());
            $this->assertSame("The delete method 'delete' was not found on the model: '$model'.", $exceptions[1]->getMessage());
            $this->assertSame($model, $exceptions[1]->getModel());
            $this->assertSame('delete', $exceptions[1]->getMethod());
            $this->assertInstanceOf($model, $exceptions[1]->getObject());
            $this->assertInternalType('array', $e->getExceptions());
            $this->assertCount(2, $e->getExceptions());
            throw $e;
        }
    }

    public function testModelIsNotPersistedASecondTimeIfClosureReturnsFalse()
    {
        $obj1 = static::$fm->create('no return:ModelWithTrackedSaves');
        $obj2 = static::$fm->create('return true:ModelWithTrackedSaves');
        $obj3 = static::$fm->create('return false:ModelWithTrackedSaves');

        $this->assertEquals(2, $obj1->saveCounter);
        $this->assertEquals(2, $obj2->saveCounter);
        $this->assertEquals(1, $obj3->saveCounter);
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
        //
    }

    public function delete()
    {
        return true;
    }
}

class ModelWithTrackedSaves
{
    public $saveCounter = 0;

    public function save()
    {
        $this->saveCounter++;

        return true;
    }

    public function delete()
    {
        return true;
    }
}
