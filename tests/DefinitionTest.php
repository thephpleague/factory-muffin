<?php

use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;
use League\FactoryMuffin\Exceptions\ModelNotFoundException;
use League\FactoryMuffin\Exceptions\NoDefinedFactoryException;
use League\FactoryMuffin\Faker\Facade as Faker;

/**
 * @group definition
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

    public function testDefineWithReplacementGenerators()
    {
        $user = static::$fm->create('UserModelStub', [
            'fullName' => Faker::name(),
        ]);

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('string', $user->fullName);
        $this->assertNotEquals('name', $user->fullName);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\ModelNotFoundException
     */
    public function testModelNotFound()
    {
        try {
            static::$fm->create($model = 'NotAClass');
        } catch (ModelNotFoundException $e) {
            $this->assertSame("No class was defined for the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            throw $e;
        }
    }

    public function testGroupDefine()
    {
        $user = static::$fm->create('group:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
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
        $this->assertInternalType('string', $user->address);
        $this->assertInternalType('string', $user->name);
        $this->assertSame('custom', $user->active);
        $this->assertContains('@', $user->email);
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

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\NoDefinedFactoryException
     */
    public function testShouldThrowExceptionWhenLoadingANonExistentGroup()
    {
        try {
            static::$fm->create('error:UserModelStub');
        } catch (NoDefinedFactoryException $e) {
            $this->assertSame("No factory definition(s) were defined for the model of type: 'error:UserModelStub'.", $e->getMessage());
            $this->assertSame('error:UserModelStub', $e->getModel());
            throw $e;
        }
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\NoDefinedFactoryException
     */
    public function testGroupDefineNoBaseModel()
    {
        try {
            static::$fm->create('foo:DogModelStub');
        } catch (NoDefinedFactoryException $e) {
            $this->assertSame("No factory definition(s) were defined for the model of type: 'DogModelStub'.", $e->getMessage());
            $this->assertSame('DogModelStub', $e->getModel());
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
