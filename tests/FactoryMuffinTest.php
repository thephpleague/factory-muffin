<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use League\FactoryMuffin\Exceptions\DefinitionNotFoundException;

/**
 * This is factory muffin test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
class FactoryMuffinTest extends AbstractTestCase
{
    public function testDefaultingToFaker()
    {
        $obj = static::$fm->instance('FakerDefaultingModelStub');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);

        $this->assertSame('http://lorempixel.com/400/600/', substr($obj->image, 0, 30));
        $this->assertNotEquals('unique::text', $obj->unique_text);
        $this->assertNotEquals('optional::text', $obj->optional_text);
    }

    public function testGetIds()
    {
        $obj = static::$fm->instance('IdTestModelStub');

        $this->assertSame(1, $obj->modelGetKey);
        $this->assertSame(1, $obj->modelPk);
        $this->assertSame(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }

    public function testShouldMakeSimpleCalls()
    {
        $obj = static::$fm->instance('ComplexModelStub');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

        $this->assertSame($expected, $obj->future);
    }

    public function testFakerDefaultBoolean()
    {
        $obj = static::$fm->instance('MainModelStub');

        $this->assertInternalType('boolean', $obj->boolean, "Asserting {$obj->boolean} is a boolean");
    }

    public function testFakerDefaultLatitude()
    {
        $obj = static::$fm->instance('MainModelStub');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function testFakerDefaultLongitude()
    {
        $obj = static::$fm->instance('MainModelStub');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DefinitionNotFoundException
     */
    public function testShouldThrowDefinitionNotFoundException()
    {
        try {
            static::$fm->instance($model = 'ModelWithNoFactoryClassStub');
        } catch (DefinitionNotFoundException $e) {
            $this->assertSame("The model definition '$model' is undefined.", $e->getMessage());
            $this->assertSame($model, $e->getDefinitionName());
            throw $e;
        }
    }

    public function testShouldAcceptClosureAsAttributeFactory()
    {
        $obj = static::$fm->instance('MainModelStub');
        $this->assertSame('just a string', $obj->text_closure);
    }

    public function testCanCreateFromStaticMethod()
    {
        $obj = static::$fm->instance('ModelWithStaticMethodFactory');

        $this->assertSame('just a string', $obj->string);
        $this->assertInstanceOf('ModelWithStaticMethodFactory', $obj->data['object']);
        $this->assertFalse($obj->data['saved']);
    }

    public function testSetAttributeUsingSetter()
    {
        $obj = static::$fm->instance('SetterTestModelWithSetter');
        $this->assertSame('Jack Sparrow', $obj->getName());
    }

    public function testCamelization()
    {
        $var = static::$fm->camelize('foo_bar');
        $this->assertSame('fooBar', $var);

        $var = static::$fm->camelize('foo');
        $this->assertSame('foo', $var);

        $var = static::$fm->camelize('foo_bar_bar');
        $this->assertSame('fooBarBar', $var);

        $var = static::$fm->camelize('foo_bar2');
        $this->assertSame('fooBar2', $var);
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

class SetterTestModelWithSetter
{
    private $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
