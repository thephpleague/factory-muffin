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

    public function test_should_make_simple_calls()
    {
        $obj = $this->factory->create('SampleModelD');

        $expected = gmdate('Y-m-d H:i:s', strtotime('+40 days'));

        $this->assertEquals($expected, $obj->future);
    }
    public function test_should_pass_simple_arguments_to_calls()
    {
        $obj = $this->factory->create('SampleModelD');

        $this->assertRegExp('|^[a-z0-9-]+$|', $obj->slug);
    }
    public function test_should_pass_factory_models_to_calls()
    {
        $obj = $this->factory->create('SampleModelD');

        $this->assertRegExp('|^[a-z0-9]+$|', $obj->munged_model);
    }
    public function test_should_get_word()
    {
        $str = $this->factory->getWord();

        $this->assertNotNull($str);
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

/**
 * Testing only
 * 
 */
class SampleModelD
{
    // Array that determines the kind of attributes
    // you would like to have
    public static $factory = array(
        'future' => 'call|fortyDaysFromNow',
        'slug' => 'call|makeSlug|text',
        'munged_model' => 'call|mungeModel|factory|SampleModelA'
    );
    public static function fortyDaysFromNow()
    {
        return gmdate('Y-m-d H:i:s', strtotime('+40 days'));
    }
    public static function makeSlug($text)
    {
        return preg_replace('|[^a-z0-9]+|', '-', $text);
    }
    public static function mungeModel($model)
    {
        $bits = explode('@', strtolower($model->email));
        return $bits[0];
    }
    public function save()
    {
        return true;
    }
}
