<?php

use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\SaveFailedException;

/**
 * @group customisation
 */
class CustomisationTest extends AbstractTestCase
{
    public function testCustomMaker()
    {
        static::$fm->setCustomMaker(function ($class) {
            return new $class('example');
        });


        $obj = static::$fm->instance('MakerCustomisationModelStub');

        $this->assertSame('example', $obj->test);

        $this->reload();
    }

    public function testCustomSetter()
    {
        static::$fm->setCustomSetter(function ($object, $name, $value) {
            $object->set($name, $value);
        });

        $obj = static::$fm->instance('SetterCustomisationModelStub');

        $this->assertSame('baz', $obj->get('bar'));
        $this->assertNull($obj->get('foo'));

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveFailedException
     */
    public function testCustomSaverFail()
    {
        static::$fm->setCustomSaver(function ($object) {
            $object->customSave();
        });

        try {
            static::$fm->create($model = 'SaverAndDeleterCustomisationModelStub');
        } catch (SaveFailedException $e) {
            $this->assertSame("We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            $this->assertNull($e->getErrors());
            $this->reload();
            throw $e;
        }

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DeletingFailedException
     */
    public function testCustomDeleterFail()
    {
        static::$fm->setCustomSaver(function ($object) {
            return $object->customSave();
        });

        static::$fm->setCustomDeleter(function ($object) {
            $object->customDelete();
        });

        try {
            static::$fm->create($model = 'SaverAndDeleterCustomisationModelStub');
            static::$fm->deleteSaved();
        } catch (DeletingFailedException $e) {
            $exceptions = $e->getExceptions();
            $this->assertSame("We encountered 1 problem(s) while trying to delete the saved models.", $e->getMessage());
            $this->assertSame("We could not delete the model of type: '$model'.", $exceptions[0]->getMessage());
            $this->reload();
            throw $e;
        }

        $this->reload();
    }

    public function testCustomSaverAndDeleter()
    {
        static::$fm->setCustomSaver(function ($object) {
            $object->test = 'foo';
            return $object->customSave();
        });

        static::$fm->setCustomDeleter(function ($object) {
            return $object->customDelete();
        });

        $obj = static::$fm->create('SaverAndDeleterCustomisationModelStub');

        $this->assertSame('foo', $obj->test);

        $this->reload();
    }
}

class MakerCustomisationModelStub
{
    public $test;

    public function __construct($test)
    {
        $this->test = $test;
    }
}

class SetterCustomisationModelStub
{
    public $attributes = array();

    public function get($name)
    {
        return array_get($this->attributes, $name);
    }

    public function set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}

class SaverAndDeleterCustomisationModelStub
{
    public $test = 'bar';

    public function save()
    {
        return false;
    }

    public function customSave()
    {
        return true;
    }

    public function delete()
    {
        return false;
    }

    public function customDelete()
    {
        return true;
    }
}
