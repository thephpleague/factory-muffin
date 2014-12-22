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

use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;
use League\FactoryMuffin\Exceptions\MissingDefinitionException;
use League\FactoryMuffin\Exceptions\MissingModelException;
use League\FactoryMuffin\Faker\Facade as Faker;

/**
 * This is definition test class.
 *
 * @group definition
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DefinitionTest extends AbstractTestCase
{
    public function testDefine()
    {
        $user = static::$fm->create('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testBasicDefinitionFunctions()
    {
        $definition = static::$fm->getDefinition('AttributeDefinitionsStub');

        $this->assertNull($definition->getGroup());
        $this->assertSame('AttributeDefinitionsStub', $definition->getClass());
    }

    public function testAttributeDefinitionFunctions()
    {
        $definition = static::$fm->getDefinition('AttributeDefinitionsStub');

        $this->assertSame([], $definition->getDefinitions());

        $definition->addDefinition('foo', 'bar');
        $this->assertSame(['foo' => 'bar'], $definition->getDefinitions());

        $definition->setDefinitions(['bar' => 'baz']);
        $this->assertSame(['foo' => 'bar', 'bar' => 'baz'], $definition->getDefinitions());

        $definition->setDefinitions([]);
        $this->assertSame(['foo' => 'bar', 'bar' => 'baz'], $definition->getDefinitions());

        $definition->clearDefinitions();
        $this->assertSame([], $definition->getDefinitions());

        $definition->setDefinitions(['bar' => 'baz', 'baz' => 'foo']);
        $this->assertSame(['bar' => 'baz', 'baz' => 'foo'], $definition->getDefinitions());
    }

    public function testCallbackDefinitionFunctions()
    {
        $definition = static::$fm->getDefinition('AttributeDefinitionsStub');

        $this->assertNull($definition->getCallback());

        $callback = function () {
            return $foo;
        };

        $definition->setCallback($callback);
        $this->assertSame($callback, $definition->getCallback());

        $definition->clearCallback();
        $this->assertNull($definition->getCallback());
    }

    public function testDefineWithReplacementGenerators()
    {
        $user = static::$fm->create('UserModelStub', [
            'fullName' => Faker::name(),
        ]);

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertSame('foo', $user->test);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('string', $user->fullName);
        $this->assertNotEquals('name', $user->fullName);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\MissingModelException
     */
    public function testModelNotFound()
    {
        try {
            static::$fm->create($model = 'NotAClass');
        } catch (MissingModelException $e) {
            $this->assertSame("The model class '$model' is undefined.", $e->getMessage());
            $this->assertSame($model, $e->getModelClass());
            throw $e;
        }
    }

    public function testGroupDefine()
    {
        $user = static::$fm->create('group:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertSame('foo', $user->test);
        $this->assertInternalType('string', $user->address);
        $this->assertNotEquals('address', $user->address);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testGroupDefineOverwrite()
    {
        $user = static::$fm->create('anothergroup:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertSame('foo', $user->test);
        $this->assertInternalType('string', $user->address);
        $this->assertInternalType('string', $user->name);
        $this->assertSame('custom', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testGroupKeepCallback()
    {
        $user = static::$fm->create('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertSame('foo', $user->test);
    }

    public function testGroupCallback()
    {
        $user = static::$fm->create('callbackgroup:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertSame('bar', $user->test);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testGroupClearAttributes()
    {
        $user = static::$fm->create('noattributes:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertSame('foo', $user->test);
        $this->assertFalse(isset($user->name));
        $this->assertFalse(isset($user->active));
        $this->assertFalse(isset($user->email));
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\MissingDefinitionException
     */
    public function testShouldThrowExceptionWhenLoadingANonExistentGroup()
    {
        try {
            static::$fm->create($model = 'error:UserModelStub');
        } catch (MissingDefinitionException $e) {
            $this->assertSame("A model definition for '$model' has not been registered.", $e->getMessage());
            $this->assertSame($model, $e->getModelClass());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\MissingDefinitionException
     */
    public function testGroupDefineNoBaseModel()
    {
        try {
            static::$fm->define('foo:DogModelStub')->setDefinitions([
                'name' => Faker::firstNameMale(),
                'age'  => Faker::numberBetween(1, 15),
            ]);
        } catch (MissingDefinitionException $e) {
            $this->assertSame("A model definition for 'DogModelStub' has not been registered.", $e->getMessage());
            $this->assertSame('DogModelStub', $e->getModelClass());
            throw $e;
        }
    }

    public function testDefineMultiple()
    {
        $user = static::$fm->create('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testSeed()
    {
        $users = static::$fm->seed(2, 'UserModelStub');

        $this->assertCount(2, $users);
        $this->assertInstanceOf('UserModelStub', $users[0]);
        $this->assertInstanceOf('UserModelStub', $users[1]);
        $this->assertNotEquals($users[0], $users[1]);
    }

    public function testInstance()
    {
        $user = static::$fm->instance('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testInstanceCallback()
    {
        $obj = static::$fm->instance('ExampleCallbackStub');
        $this->assertSame('yaycalled', $obj->callback);
        $this->assertFalse($obj->saved);
    }

    public function testCreateCallback()
    {
        $obj = static::$fm->create('AnotherCallbackStub');
        $this->assertSame('hello there', $obj->foo);
        $this->assertTrue($obj->saved);
    }

    public function testFactoryLoading()
    {
        $count = count(get_included_files());

        $return = static::$fm->loadFactories(__DIR__.'/stubs');

        $this->assertSame(1, count(get_included_files()) - $count);
        $this->assertInstanceOf('League\FactoryMuffin\FactoryMuffin', $return);

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DirectoryNotFoundException
     */
    public function testShouldThrowExceptionWhenLoadingANonExistentDirectory()
    {
        try {
            static::$fm->loadFactories($path = __DIR__.'/thisdirectorydoesntexist');
        } catch (DirectoryNotFoundException $e) {
            $this->assertSame("The directory '$path' was not found.", $e->getMessage());
            $this->assertSame($path, $e->getPath());
            throw $e;
        }
    }

    public function testGetGeneratorFactory()
    {
        $this->assertInstanceOf('League\FactoryMuffin\Generators\GeneratorFactory', static::$fm->getGeneratorFactory());
    }

    public function testFactoryIsBoundToClosure()
    {
        $obj = static::$fm->instance('UserModelStub', [
            'profile' => function () {
                return $this->instance('ProfileModelStub');
            }
        ]);

        $this->assertInstanceOf('ProfileModelStub', $obj->profile);
    }

    public function testCustomMaker()
    {
        $obj = static::$fm->instance('CustomMakerStub');

        $this->assertInstanceOf('CustomMakerStub', $obj);
        $this->assertSame('qwerty', $obj->foo);
    }

    public function testCustomMakerGroup()
    {
        $obj = static::$fm->instance('group:CustomMakerStub');

        $this->assertInstanceOf('CustomMakerStub', $obj);
        $this->assertSame('qwertyuiop', $obj->foo);
    }

    public function testNoMakerGroup()
    {
        $obj = static::$fm->instance('clear:CustomMakerStub');

        $this->assertInstanceOf('CustomMakerStub', $obj);
        $this->assertSame('bar', $obj->foo);
    }
}

class AttributeDefinitionsStub
{
    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}

class UserModelStub
{
    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}

class ProfileModelStub
{
    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}

class DogModelStub
{
    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}

class ExampleDefinedModelStub
{
    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}

class ExampleCallbackStub
{
    public $callback;
}

class AnotherCallbackStub
{
    public $foo;

    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}

class CustomMakerStub
{
    public $foo;

    public function __construct($foo = 'bar')
    {
        $this->foo = $foo;
    }

    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}
