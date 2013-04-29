<?php

use Zizaco\FactoryMuff\FactoryMuff;

class FactoryMuffTest extends PHPUnit_Framework_TestCase {

    protected $factory;

    public function setUp()
    {
        $this->factory = new FactoryMuff();
    }

    public function test_should_get_attributes_for()
    {
        $attr = $this->factory->attributesFor('SampleModelA');

        foreach ($attr as $value) {
            $this->assertEquals( 'string' ,gettype($value) );
        }
        
        $this->assertTrue( is_numeric($attr['modelb_id']) );
    }

    public function test_should_create()
    {
        $obj = $this->factory->create('SampleModelA');
        
        $this->assertTrue( is_numeric($obj->id) );
    }

    public function test_should_throw_exception_on_model_save_failure()
    {
        $this->setExpectedException('\Zizaco\FactoryMuff\SaveException');
        $obj = $this->factory->create('SampleModelC');
    }
}

/**
* Testing only
*
*/
class SampleModelA
{
    // Array that determines the kind of attributes
    // you would like to have
    public static $factory = array(
        'modelb_id' => 'factory|SampleModelB',
        'name' => 'string',
        'email' => 'email',
        'message' => 'text',
    );

    public function save()
    {
        $this->id = date('U');
        return true;
    }
}


/**
* Testing only
*
*/
class SampleModelB extends SampleModelA
{
    // Array that determines the kind of attributes
    // you would like to have
    public static $factory = array(
        'title' => 'string',
        'email' => 'email',
        'content' => 'text',
    );
}


/**
* Testing only
*
*/
class SampleModelC
{
    // Array that determines the kind of attributes
    // you would like to have
    public static $factory = array(
    );

    // Eloquent models return False on save failure.
    // We *might* want to throw exceptions in that case.
    public function save()
    {
        return false;
    }
}
