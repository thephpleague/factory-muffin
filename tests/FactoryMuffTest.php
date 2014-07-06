<?php

use Zizaco\FactoryMuff\FactoryMuff;

class FactoryMuffTest extends PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new FactoryMuff();
    }

    public function test_defaulting_to_faker()
    {
        $obj = $this->factory->create('SampleModelB');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);
    }

    public function test_should_get_attributes_for()
    {
        $attr = $this->factory->attributesFor('SampleModelA');

        foreach ($attr as $value) {
            $this->assertInternalType('string', $value);
        }

        $this->assertTrue(is_numeric($attr['modelb_id']) );
    }

    public function test_date_kind()
    {
        $format = 'Y-m-d';

        $this->factory->define('SampleModelA', array(
            'created' => 'date|' . $format,
        ));

        $obj = $this->factory->create('SampleModelA');
        $dateTime = \DateTime::createFromFormat($format, $obj->created);
        $this->assertEquals($obj->created, $dateTime->format($format));
    }

    public function test_integer()
    {
        $this->factory->define('SampleModelA', array(
            'number' => 'integer|9',
        ));

        $obj = $this->factory->create('SampleModelA');
        $this->assertEquals(9, strlen($obj->number));
    }

    public function test_name()
    {
        $this->factory->define('SampleModelA', array(
            'name' => 'name',
        ));

        $obj = $this->factory->create('SampleModelA');
        $this->assertTrue(strlen($obj->name) > 0);

        $this->factory->define('SampleModelA', array(
            'name' => 'name|female',
        ));

        $obj = $this->factory->create('SampleModelA');
        $this->assertTrue(strlen($obj->name) > 0);
    }

    public function test_string()
    {
        $this->factory->define('SampleModelA', array(
            'string' => 'string|4',
        ));

        $obj = $this->factory->create('SampleModelA');
        $this->assertEquals(4, strlen($obj->string));
    }

    public function test_text()
    {
        $this->factory->define('SampleModelA', array(
            'text' => 'text|4',
        ));

        $obj = $this->factory->create('SampleModelA');
        $this->assertEquals(4, strlen($obj->text));
    }

    public function test_text_default()
    {
        $this->factory->define('SampleModelA', array(
            'text' => 'text',
        ));

        $obj = $this->factory->create('SampleModelA');
        $this->assertEquals(100, strlen($obj->text));
    }

    public function test_should_create()
    {
        $obj = $this->factory->create('SampleModelA');

        $this->assertTrue(is_numeric($obj->id));
    }

    public function test_get_ids()
    {
        $obj = $this->factory->create('SampleModelF');

        $this->assertEquals(1, $obj->modelGetKey);
        $this->assertEquals(1, $obj->modelPk);
        $this->assertEquals(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }

    public function test_should_throw_exception_on_model_save_failure()
    {
        $this->setExpectedException('\Zizaco\FactoryMuff\SaveException');

        $obj = $this->factory->create('SampleModelC');
    }

    public function test_should_make_simple_calls()
    {
        $obj = $this->factory->create('SampleModelD');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

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

        $this->assertRegExp("|^[a-z0-9.']+$|", $obj->munged_model);
    }

    public function test_should_create_based_on_define_declaration()
    {
        $this->factory->define('SampleModelA', array(
            'text' => 'just a string',
        ));

        $obj = $this->factory->create('SampleModelA');

        $this->assertEquals('just a string', $obj->text);

        $this->factory->define('SampleModelA', array(
            'text' => 'sneakyString',
        ));

        $obj = $this->factory->create('SampleModelA');

        $this->assertEquals('sneakyString', $obj->text);
    }

    public function test_faker_default_boolean()
    {
        $this->factory->define('SampleModelA', array(
            'something' => 'boolean',
        ));

        $obj = $this->factory->create('SampleModelA');

        $this->assertInternalType('boolean', $obj->something, "Asserting {$obj->something} is a boolean");
    }

    public function test_faker_default_latitude()
    {
        $this->factory->define('SampleModelA', array(
            'lat' => 'latitude',
        ));

        $obj = $this->factory->create('SampleModelA');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function test_faker_default_longitude()
    {
        $this->factory->define('SampleModelA', array(
            'lon' => 'longitude',
        ));

        $obj = $this->factory->create('SampleModelA');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    /**
     * @expectedException Zizaco\FactoryMuff\NoDefinedFactoryException
     */
    public function test_should_throw_exception_when_no_defined_factory()
    {
        $obj = $this->factory->create('SampleModelE');
    }

    public function test_should_accept_closure_as_attribute_factory()
    {
        $this->factory->define('SampleModelA', array(
            'text' => function () {
                return 'just a string';
            },
        ));

        $obj = $this->factory->create('SampleModelA');

        $this->assertEquals('just a string', $obj->text);

    }

    public function test_can_create_from_static_method()
    {
        $this->factory->create('ModelWithStaticMethodFactory');

        $obj = $this->factory->create('ModelWithStaticMethodFactory');

        $this->assertEquals('just a string', $obj->string);
        $this->assertEquals(4, $obj->four);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage does not have a static doesNotExist method
     */
    public function test_throw_exception_when_invalid_static_method()
    {
        $obj = $this->factory->create('ModelWithMissingStaticMethod');
        $obj->does_not_exist;
    }

    /**
     * @expectedException \Zizaco\FactoryMuff\SaveException
     * @expectedExceptionMessage Failed to save. - Could not save the model of type: SampleModelWithValidationErrors
     */
    public function test_with_validation_errors()
    {
        $obj = $this->factory->create('SampleModelWithValidationErrors');
    }
}

class SampleModelWithValidationErrors
{
    public $factory = array();
    public $validationErrors = 'Failed to save.';

    public function save()
    {

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
        'message' => 'text'
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
        'card' => 'creditCardDetails'
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
        return gmdate('Y-m-d', strtotime('+40 days'));
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

class SampleModelE
{
    public function save()
    {
        return true;
    }
}

class SampleModelF
{

    public static $factory = array(
        'modelGetKey' => 'factory|SampleModelGetKey',
        'modelPk' => 'factory|SampleModelPk',
        'model_id' => 'factory|SampleModel_id',
        'model_null' => 'factory|SampleModel_null',
    );

    public function save()
    {
        return true;
    }
}

class SampleModelGetKey
{
    public static $factory = array();
    public function getKey()
    {
        return 1;
    }

    public function save()
    {
        return true;
    }
}

class SampleModelPk
{
    public static $factory = array();
    public function pk()
    {
        return 1;
    }

    public function save()
    {
        return true;
    }
}

class SampleModel_id
{
    public static $factory = array();
    public $_id = 1;

    public function save()
    {
        return true;
    }
}

class SampleModel_null
{
    public static $factory = array();

    public function save()
    {
        return true;
    }
}

class ModelWithMissingStaticMethod
{
    public static $factory = array(
        'does_not_exist' => 'call|doesNotExist'
    );
}

class ModelWithStaticMethodFactory
{
    public static function factory()
    {
        return array(
            'string' => 'just a string',
            'four' => function () {
                return 2 + 2;
            }
        );
    }

    public function save()
    {
        return true;
    }
}
