<?php

use League\FactoryMuffin\Exception\NoDefinedFactory;
use League\FactoryMuffin\Exception\MethodNotFound;
use League\FactoryMuffin\Facade\FactoryMuffin;

/**
 * @group main
 */
class FactoryMuffinTest extends AbstractTestCase
{
    public function testDefaultingToFaker()
    {
        $obj = FactoryMuffin::instance('ModelBStub');
        $this->assertInternalType('array', $obj->card);
        $this->assertArrayHasKey('type', $obj->card);
        $this->assertArrayHasKey('number', $obj->card);
        $this->assertArrayHasKey('name', $obj->card);
        $this->assertArrayHasKey('expirationDate', $obj->card);
    }

    public function testShouldGetAttributesFor()
    {
        $attr = FactoryMuffin::attributesFor('ModelAStub');
        $this->assertEquals(4, strlen($attr['string_4']));
    }

    public function testDateKind()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
        $dateTime = DateTime::createFromFormat('Y-m-d', $obj->created);
        $this->assertEquals($obj->created, $dateTime->format('Y-m-d'));
    }

    public function testInteger()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
        $this->assertEquals(9, strlen($obj->number));
    }

    public function testName()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
        $this->assertTrue(strlen($obj->full_name) > 0);
    }

    public function testString()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
        $this->assertEquals(4, strlen($obj->string_4));
    }

    public function testText()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
        $this->assertEquals(4, strlen($obj->text_4));
    }

    public function testTextDefault()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
        $this->assertEquals(100, strlen($obj->text_100));
    }

    public function testGetIds()
    {
        $obj = FactoryMuffin::instance('ModelFStub');

        $this->assertEquals(1, $obj->modelGetKey);
        $this->assertEquals(1, $obj->modelPk);
        $this->assertEquals(1, $obj->model_id);
        $this->assertNull($obj->model_null);
    }

    public function testShouldMakeSimpleCalls()
    {
        $obj = FactoryMuffin::instance('ModelDStub');

        $expected = gmdate('Y-m-d', strtotime('+40 days'));

        $this->assertEquals($expected, $obj->future);
    }
    public function testShouldPassSimpleArgumentsToCalls()
    {
        $obj = FactoryMuffin::instance('ModelDStub');

        $this->assertRegExp('|^[a-z0-9-]+$|', $obj->slug);
    }
    public function testShouldPassFactoryModelsToCalls()
    {
        $obj = FactoryMuffin::instance('ModelDStub');

        $this->assertRegExp("|^[a-z0-9.']+$|", $obj->munged_model);
    }

    public function testFakerDefaultBoolean()
    {
        $obj = FactoryMuffin::instance('ModelAStub');

        $this->assertInternalType('boolean', $obj->boolean, "Asserting {$obj->boolean} is a boolean");
    }

    public function testFakerDefaultLatitude()
    {
        $obj = FactoryMuffin::instance('ModelAStub');

        $this->assertGreaterThanOrEqual(-90, $obj->lat);
        $this->assertLessThanOrEqual(90, $obj->lat);
    }

    public function testFakerDefaultLongitude()
    {
        $obj = FactoryMuffin::instance('ModelAStub');

        $this->assertGreaterThanOrEqual(-180, $obj->lon);
        $this->assertLessThanOrEqual(180, $obj->lon);
    }

    public function testShouldThrowExceptionWhenNoDefinedFactory()
    {
        try {
            FactoryMuffin::instance($model = 'ModelEStub');
        } catch (NoDefinedFactory $e) {
            $this->assertEquals("No factory class was defined for the model of type: '$model'.", $e->getMessage());
            $this->assertEquals($model, $e->getModel());
        }
    }

    public function testShouldAcceptClosureAsAttributeFactory()
    {
        $obj = FactoryMuffin::instance('ModelAStub');
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
}


class ModelAStub
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

class ModelBStub extends ModelAStub
{
    //
}

class ModelDStub
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

class ModelEStub
{
    public function save()
    {
        return true;
    }
}

class ModelFStub
{
    public function save()
    {
        return true;
    }
}

class ModelFGetKeyStub
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

class ModelFPkStub
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

class ModelFIdStub
{
    public $_id = 1;

    public function save()
    {
        return true;
    }
}

class ModelFNullStub
{
    public function save()
    {
        return true;
    }
}

class ModelWithMissingStaticMethod
{
    //
}

class ModelWithStaticMethodFactory
{
    public function save()
    {
        return true;
    }
}
