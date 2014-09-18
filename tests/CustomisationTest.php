<?php

use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\SaveFailedException;
use League\FactoryMuffin\Facades\FactoryMuffin;

/**
 * @group customisation
 */
class CustomisationTest extends AbstractTestCase
{
    public function testCustomMaker()
    {
        FactoryMuffin::setCustomMaker(function ($class) {
            return new $class('example');
        });


        $obj = FactoryMuffin::instance('MakerCustomisationModelStub');

        $this->assertSame('example', $obj->test);

        $this->reload();
    }

    public function testCustomSetter()
    {
        FactoryMuffin::setCustomSetter(function ($object, $name, $value) {
            $object->set($name, $value);
        });

        $obj = FactoryMuffin::instance('SetterCustomisationModelStub');

        $this->assertSame('baz', $obj->get('bar'));
        $this->assertNull($obj->get('foo'));

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\SaveFailedException
     */
    public function testCustomSaverFail()
    {
        FactoryMuffin::setCustomSaver(function ($object) {
            $object->customSave();
        });

        try {
            FactoryMuffin::create($model = 'SaverAndDeleterCustomisationModelStub');
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
        FactoryMuffin::setCustomSaver(function ($object) {
            return $object->customSave();
        });

        FactoryMuffin::setCustomDeleter(function ($object) {
            $object->customDelete();
        });

        try {
            FactoryMuffin::create($model = 'SaverAndDeleterCustomisationModelStub');
            FactoryMuffin::deleteSaved();
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
        FactoryMuffin::setCustomSaver(function ($object) {
            $object->test = 'foo';
            return $object->customSave();
        });

        FactoryMuffin::setCustomDeleter(function ($object) {
            return $object->customDelete();
        });

        $obj = FactoryMuffin::create('SaverAndDeleterCustomisationModelStub');

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
