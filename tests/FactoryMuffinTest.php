<?php

namespace League\FactoryMuffin\Test;

use League\FactoryMuffin\Facade\FactoryMuffin;

class FactoryMuffinTest extends \PHPUnit_Framework_TestCase
{
    public function test_defaulting_to_faker()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelB');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);
    }

    public function test_should_get_attributes_for()
    {
        $attr = FactoryMuffin::attributesFor('League\FactoryMuffin\Test\SampleModelA');
        $this->assertTrue(is_numeric($attr['modelb_id']) );
    }

    public function test_date_kind()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $dateTime = \DateTime::createFromFormat('Y-m-d', $obj->created);
        $this->assertEquals($obj->created, $dateTime->format('Y-m-d'));
    }

    public function test_integer()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(9, strlen($obj->number));
    }

    public function test_name()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertTrue(strlen($obj->full_name) > 0);
    }

    public function test_string()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(4, strlen($obj->string_4));
    }

    public function test_text()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(4, strlen($obj->text_4));
    }

    public function test_text_default()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(100, strlen($obj->text_100));
    }

    public function test_should_create()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertTrue(is_numeric($obj->id));
    }

    public function test_get_ids()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelF');

        $this->assertEquals(1, $obj->modelGetKey);
        $this->assertEquals(1, $obj->modelPk);
        $this->assertEquals(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }
    /**
     * @expectedException League\FactoryMuffin\Exception\Save
     */
    public function test_should_throw_exception_on_model_save_failure()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelC');
    }

    public function test_should_make_simple_calls()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelD');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

        $this->assertEquals($expected, $obj->future);
    }
    public function test_should_pass_simple_arguments_to_calls()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelD');

        $this->assertRegExp('|^[a-z0-9-]+$|', $obj->slug);
    }
    public function test_should_pass_factory_models_to_calls()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelD');

        $this->assertRegExp("|^[a-z0-9.']+$|", $obj->munged_model);
    }

    public function test_faker_default_boolean()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');

        $this->assertInternalType('boolean', $obj->boolean, "Asserting {$obj->boolean} is a boolean");
    }

    public function test_faker_default_latitude()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function test_faker_default_longitude()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    /**
     * @expectedException League\FactoryMuffin\Exception\NoDefinedFactory
     */
    public function test_should_throw_exception_when_no_defined_factory()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelE');
    }

    public function test_should_accept_closure_as_attribute_factory()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals('just a string', $obj->text_closure);
    }

    public function test_can_create_from_static_method()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\ModelWithStaticMethodFactory');

        $this->assertEquals('just a string', $obj->string);
        $this->assertEquals(4, $obj->four);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage does not have a static doesNotExist method
     */
    public function test_throw_exception_when_invalid_static_method()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\ModelWithMissingStaticMethod');
        $obj->does_not_exist;
    }

    /**
     * @expectedException \League\FactoryMuffin\Exception\Save
     * @expectedExceptionMessage Failed to save. - Could not save the model of type: League\FactoryMuffin\Test\SampleModelWithValidationErrors
     */
    public function test_with_validation_errors()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelWithValidationErrors');
    }
}

class SampleModelWithValidationErrors
{
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
}

/**
* Testing only
*
*/
class SampleModelC
{
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
    public function save()
    {
        return true;
    }
}

class SampleModelGetKey
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

class SampleModelPk
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

class SampleModel_id
{
    public $_id = 1;

    public function save()
    {
        return true;
    }
}

class SampleModel_null
{
    public function save()
    {
        return true;
    }
}

class ModelWithMissingStaticMethod
{
}

class ModelWithStaticMethodFactory
{
    public function save()
    {
        return true;
    }
}
