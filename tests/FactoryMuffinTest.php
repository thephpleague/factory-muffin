<?php

namespace League\FactoryMuffin\Test;

use League\FactoryMuffin\Facade\FactoryMuffin;

class FactoryMuffinTest extends TestCase
{
    public function testDefaultingToFaker()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelB');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);
    }

    public function testShouldGetAttributesFor()
    {
        $attr = FactoryMuffin::attributesFor('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(4, strlen($attr['string_4']));
    }

    public function testDateKind()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $dateTime = \DateTime::createFromFormat('Y-m-d', $obj->created);
        $this->assertEquals($obj->created, $dateTime->format('Y-m-d'));
    }

    public function testInteger()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(9, strlen($obj->number));
    }

    public function testName()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertTrue(strlen($obj->full_name) > 0);
    }

    public function testString()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(4, strlen($obj->string_4));
    }

    public function testText()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(4, strlen($obj->text_4));
    }

    public function testTextDefault()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals(100, strlen($obj->text_100));
    }

    public function testShouldCreate()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertTrue(is_numeric($obj->id));
    }

    public function testGetIds()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelF');

        $this->assertEquals(1, $obj->modelGetKey);
        $this->assertEquals(1, $obj->modelPk);
        $this->assertEquals(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }
    /**
     * @expectedException \League\FactoryMuffin\Exception\Save
     * @expectedExceptionMessage We could not save the model of type: 'League\FactoryMuffin\Test\SampleModelC'.
     */
    public function testShouldThrowExceptionOnModelSaveFailure()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelC');
    }

    public function testShouldMakeSimpleCalls()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelD');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

        $this->assertEquals($expected, $obj->future);
    }
    public function testShouldPassSimpleArgumentsToCalls()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelD');

        $this->assertRegExp('|^[a-z0-9-]+$|', $obj->slug);
    }
    public function testShouldPassFactoryModelsToCalls()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelD');

        $this->assertRegExp("|^[a-z0-9.']+$|", $obj->munged_model);
    }

    public function testFakerDefaultBoolean()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');

        $this->assertInternalType('boolean', $obj->boolean, "Asserting {$obj->boolean} is a boolean");
    }

    public function testFakerDefaultLatitude()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function testFakerDefaultLongitude()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exception\NoDefinedFactory
     * @expectedExceptionMessage No factory class was defined for the model of type: 'League\FactoryMuffin\Test\SampleModelE'.
     */
    public function testShouldThrowExceptionWhenNoDefinedFactory()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelE');
    }

    public function testShouldAcceptClosureAsAttributeFactory()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\SampleModelA');
        $this->assertEquals('just a string', $obj->text_closure);
    }

    public function testCanCreateFromStaticMethod()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\ModelWithStaticMethodFactory');

        $this->assertEquals('just a string', $obj->string);
        $this->assertEquals(4, $obj->four);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exception\MethodNotFound
     * @expectedExceptionMessage The static method 'doesNotExist' was not found on the model of type: 'League\FactoryMuffin\Test\ModelWithMissingStaticMethod'.
     */
    public function testThrowExceptionWhenInvalidStaticMethod()
    {
        $obj = FactoryMuffin::create('League\FactoryMuffin\Test\ModelWithMissingStaticMethod');
        $obj->does_not_exist;
    }

    /**
     * @expectedException \League\FactoryMuffin\Exception\Save
     * @expectedExceptionMessage Failed to save. We could not save the model of type: 'League\FactoryMuffin\Test\SampleModelWithValidationErrors'.
     */
    public function testWithValidationErrors()
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
