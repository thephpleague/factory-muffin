<?php

/*
 * This file is part of Factory Muffin.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use League\FactoryMuffin\Exceptions\MissingDefinitionException;

/**
 * This is factory muffin test class.
 *
 * @group main
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
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

        $this->assertSame('http://lorempixel.com/400/600/', $obj->image);
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
     * @expectedException \League\FactoryMuffin\Exceptions\MissingDefinitionException
     */
    public function testShouldThrowExceptionWhenMissingDefinitionException()
    {
        try {
            static::$fm->instance($model = 'ModelWithNoFactoryClassStub');
        } catch (MissingDefinitionException $e) {
            $this->assertSame("A model definition for '$model' has not been registered.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
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
