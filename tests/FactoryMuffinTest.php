<?php

use League\FactoryMuffin\Exceptions\NoDefinedFactoryException;
use League\FactoryMuffin\Facades\FactoryMuffin;

/**
 * @group main
 */
class FactoryMuffinTest extends AbstractTestCase
{
    public function testDefaultingToFaker()
    {
        $obj = FactoryMuffin::instance('FakerDefaultingModelStub');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);

        $this->assertSame('http://lorempixel.com/400/600/', $obj->image);
        $this->assertNotEquals('unique::text', $obj->unique_text);
        $this->assertNotEquals('optional::text', $obj->optional_text);
    }

    public function testGetIds()
    {
        $obj = FactoryMuffin::instance('IdTestModelStub');

        $this->assertSame(1, $obj->modelGetKey);
        $this->assertSame(1, $obj->modelPk);
        $this->assertSame(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }

    public function testShouldMakeSimpleCalls()
    {
        $obj = FactoryMuffin::instance('ComplexModelStub');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

        $this->assertSame($expected, $obj->future);
    }

    public function testFakerDefaultBoolean()
    {
        $obj = FactoryMuffin::instance('MainModelStub');

        $this->assertInternalType('boolean', $obj->boolean, "Asserting {$obj->boolean} is a boolean");
    }

    public function testFakerDefaultLatitude()
    {
        $obj = FactoryMuffin::instance('MainModelStub');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function testFakerDefaultLongitude()
    {
        $obj = FactoryMuffin::instance('MainModelStub');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\NoDefinedFactoryException
     */
    public function testShouldThrowExceptionWhenNoDefinedFactoryException()
    {
        try {
            FactoryMuffin::instance($model = 'ModelWithNoFactoryClassStub');
        } catch (NoDefinedFactoryException $e) {
            $this->assertSame("No factory definition(s) were defined for the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            throw $e;
        }
    }

    public function testShouldAcceptClosureAsAttributeFactory()
    {
        $obj = FactoryMuffin::instance('MainModelStub');
        $this->assertSame('just a string', $obj->text_closure);
    }

    public function testCanCreateFromStaticMethod()
    {
        $obj = FactoryMuffin::instance('ModelWithStaticMethodFactory');

        $this->assertSame('just a string', $obj->string);
        $this->assertInstanceOf('ModelWithStaticMethodFactory', $obj->data['object']);
        $this->assertFalse($obj->data['saved']);
    }
}

class MainModelStub
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

class FakerDefaultingModelStub extends MainModelStub
{
    //
}

class ComplexModelStub
{
    public static function fortyDaysFromNow()
    {
        return gmdate('Y-m-d', strtotime('+40 days'));
    }

    public function save()
    {
        return true;
    }
}

class ModelWithNoFactoryClassStub
{
    public function save()
    {
        return true;
    }
}

class IdTestModelStub
{
    public function save()
    {
        return true;
    }
}

class IdTestModelGetKeyStub
{
    public function getKey()
    {
        return 1;
    }

    public function save()
    {
        return true;
    }
}

class IdTestModelPkStub
{
    public function pk()
    {
        return 1;
    }

    public function save()
    {
        return true;
    }
}

class IdTestModelIdStub
{
    public $_id = 1;

    public function save()
    {
        return true;
    }
}

class IdTestModelNullStub
{
    public function save()
    {
        return true;
    }
}

class ModelWithStaticMethodFactory
{
    public function save()
    {
        return true;
    }
}
