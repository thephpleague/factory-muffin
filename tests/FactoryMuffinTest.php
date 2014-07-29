<?php

use League\FactoryMuffin\Exception\NoDefinedFactory;
use League\FactoryMuffin\Exception\MethodNotFound;
use League\FactoryMuffin\Exception\SaveFailed;
use League\FactoryMuffin\Facade\FactoryMuffin;

class FactoryMuffinTest extends AbstractTestCase
{
    public function testDefaultingToFaker()
    {
        $obj = FactoryMuffin::instance('SampleModelB');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);
    }

    public function testShouldGetAttributesFor()
    {
        $attr = FactoryMuffin::attributesFor('SampleModelA');
        $this->assertEquals(4, strlen($attr['string_4']));
    }

    public function testDateKind()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $dateTime = DateTime::createFromFormat('Y-m-d', $obj->created);
        $this->assertEquals($obj->created, $dateTime->format('Y-m-d'));
    }

    public function testInteger()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $this->assertEquals(9, strlen($obj->number));
    }

    public function testName()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $this->assertTrue(strlen($obj->full_name) > 0);
    }

    public function testString()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $this->assertEquals(4, strlen($obj->string_4));
    }

    public function testText()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $this->assertEquals(4, strlen($obj->text_4));
    }

    public function testTextDefault()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $this->assertEquals(100, strlen($obj->text_100));
    }

    public function testShouldCreate()
    {
        $obj = FactoryMuffin::create('SampleModelA');
        $this->assertTrue(is_numeric($obj->id));
    }

    public function testGetIds()
    {
        $obj = FactoryMuffin::instance('SampleModelF');

        $this->assertEquals(1, $obj->modelGetKey);
        $this->assertEquals(1, $obj->modelPk);
        $this->assertEquals(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }

    public function testShouldThrowExceptionOnModelSaveFailure()
    {
        try {
            FactoryMuffin::create($model = 'SampleModelC');
        } catch (SaveFailed $e) {
            $this->assertEquals("We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertNull($e->getErrors());
        }
    }

    public function testShouldMakeSimpleCalls()
    {
        $obj = FactoryMuffin::instance('SampleModelD');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

        $this->assertEquals($expected, $obj->future);
    }
    public function testShouldPassSimpleArgumentsToCalls()
    {
        $obj = FactoryMuffin::instance('SampleModelD');

        $this->assertRegExp('|^[a-z0-9-]+$|', $obj->slug);
    }
    public function testShouldPassFactoryModelsToCalls()
    {
        $obj = FactoryMuffin::instance('SampleModelD');

        $this->assertRegExp("|^[a-z0-9.']+$|", $obj->munged_model);
    }

    public function testFakerDefaultBoolean()
    {
        $obj = FactoryMuffin::instance('SampleModelA');

        $this->assertInternalType('boolean', $obj->boolean, "Asserting {$obj->boolean} is a boolean");
    }

    public function testFakerDefaultLatitude()
    {
        $obj = FactoryMuffin::instance('SampleModelA');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function testFakerDefaultLongitude()
    {
        $obj = FactoryMuffin::instance('SampleModelA');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    public function testShouldThrowExceptionWhenNoDefinedFactory()
    {
        try {
            FactoryMuffin::instance($model = 'SampleModelE');
        } catch (NoDefinedFactory $e) {
            $this->assertEquals("No factory class was defined for the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
        }
    }

    public function testShouldAcceptClosureAsAttributeFactory()
    {
        $obj = FactoryMuffin::instance('SampleModelA');
        $this->assertEquals('just a string', $obj->text_closure);
    }

    public function testCanCreateFromStaticMethod()
    {
        $obj = FactoryMuffin::instance('ModelWithStaticMethodFactory');

        $this->assertEquals('just a string', $obj->string);
        $this->assertEquals(4, $obj->four);
    }

    public function testThrowExceptionWhenInvalidStaticMethod()
    {
        try {
            $obj = FactoryMuffin::create($model = 'ModelWithMissingStaticMethod');
        } catch (MethodNotFound $e) {
            $this->assertEquals("The static method 'doesNotExist' was not found on the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('doesNotExist', $e->getMethod());
        }
    }

    public function testWithValidationErrors()
    {
        try {
            FactoryMuffin::create($model = 'SampleModelWithValidationErrors');
        } catch (SaveFailed $e) {
            $this->assertEquals("Failed to save. We could not save the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
            $this->assertEquals('Failed to save.', $e->getErrors());
        }
    }
}

class SampleModelWithValidationErrors
{
    public $validationErrors = 'Failed to save.';

    public function save()
    {

    }

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
    {
        return true;
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

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
    {
        return true;
    }
}

/**
* Testing only
*
*/
class SampleModelB extends SampleModelA
{
    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
    {
        return true;
    }
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

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
    {
        return true;
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

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
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

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
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

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
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

    /**
     * @todo This can be removed once we resolve #97
     */
    public function delete()
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
