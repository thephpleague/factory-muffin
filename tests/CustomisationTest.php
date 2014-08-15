<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * @group customisation
 */
class CustomisationTest extends AbstractTestCase
{

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCustomMaker()
    {
        FactoryMuffin::setCustomMaker(function($class) {
            return new $class('example');
        });


        $obj = FactoryMuffin::instance('MakerCustomisationModelStub');

        $this->assertSame('example', $obj->test);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCustomSetter()
    {
        FactoryMuffin::setCustomSetter(function($object, $name, $value) {
            $object->set($name, $value);
        });

        $obj = FactoryMuffin::instance('SetterCustomisationModelStub');

        $this->assertSame('baz', $obj->get('bar'));
        $this->assertNull($obj->get('foo'));
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
