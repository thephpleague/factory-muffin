<?php

use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;
use League\FactoryMuffin\Exceptions\ModelNotFoundException;
use League\FactoryMuffin\Exceptions\NoDefinedFactoryException;
use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * @group definition
 */
class DefinitionTest extends AbstractTestCase
{
    public function testDefine()
    {
        $user = FactoryMuffin::create('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testDefineWithReplacementGenerators()
    {
        $user = FactoryMuffin::create('UserModelStub', array(
            'fullName' => 'name'
        ));

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('string', $user->fullName);
        $this->assertNotEquals('name', $user->fullName);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testDefineWithReplacementGeneratorsOverwrite()
    {
        $user = FactoryMuffin::create('UserModelStub', array(
            'age' => 'numberBetween|50;50'
        ));

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
        $this->assertInternalType('integer', $user->age);
        $this->assertSame(50, $user->age);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\ModelNotFoundException
     */
    public function testModelNotFound()
    {
        try {
            FactoryMuffin::create($model = 'NotAClass');
        } catch (ModelNotFoundException $e) {
            $this->assertSame("No class was defined for the model of type: '$model'.", $e->getMessage());
            $this->assertSame($model, $e->getModel());
            throw $e;
        }
    }

    public function testGroupDefine()
    {
        $user = FactoryMuffin::create('group:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->address);
        $this->assertNotEquals('address', $user->address);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testGroupDefineOverwrite()
    {
        $user = FactoryMuffin::create('anothergroup:UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->address);
        $this->assertInternalType('string', $user->name);
        $this->assertNotInternalType('boolean', $user->active);
        $this->assertSame('false', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testGroupDefineWithReplacementGeneratorsOverwrite()
    {
        $user = FactoryMuffin::create('centenarian:UserModelStub', array(
            'age' => 'numberBetween|50;50'
        ));

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
        $this->assertInternalType('integer', $user->age);

        $this->assertSame(50, $user->age);
    }

    public function testGroupCallback()
    {
        $user = FactoryMuffin::create('callbackgroup:UserModelStub');

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
            FactoryMuffin::create('error:UserModelStub');
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
            FactoryMuffin::create('foo:DogModelStub');
        } catch (NoDefinedFactoryException $e) {
            $this->assertSame("No factory definition(s) were defined for the model of type: 'DogModelStub'.", $e->getMessage());
            $this->assertSame('DogModelStub', $e->getModel());
            throw $e;
        }
    }

    public function testDefineMultiple()
    {
        $user = FactoryMuffin::create('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testSeed()
    {
        $users = FactoryMuffin::seed(2, 'UserModelStub');

        $this->assertCount(2, $users);
        $this->assertInstanceOf('UserModelStub', $users[0]);
        $this->assertInstanceOf('UserModelStub', $users[1]);
        $this->assertNotEquals($users[0], $users[1]);
    }

    public function testInstance()
    {
        $user = FactoryMuffin::instance('UserModelStub');

        $this->assertInstanceOf('UserModelStub', $user);
        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testInstanceCallback()
    {
        $obj = FactoryMuffin::instance('ExampleCallbackStub');
        $this->assertSame('yaycalled', $obj->callback);
        $this->assertFalse($obj->saved);
    }

    public function testCreateCallback()
    {
        $obj = FactoryMuffin::create('AnotherCallbackStub');
        $this->assertSame('hello there', $obj->foo);
        $this->assertTrue($obj->saved);
    }

    public function testAttributesFor()
    {
        $object = new UserModelStub();
        $attributes = FactoryMuffin::attributesFor($object);

        $this->assertInternalType('string', $attributes['name']);
        $this->assertInternalType('boolean', $attributes['active']);
        $this->assertContains('@', $attributes['email']);
    }

    public function testFactoryLoading()
    {
        $count = count(get_included_files());

        $return = FactoryMuffin::loadFactories(__DIR__.'/stubs');

        $this->assertSame(1, count(get_included_files()) - $count);
        $this->assertInstanceOf('League\FactoryMuffin\Factory', $return);

        $this->reload();
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DirectoryNotFoundException
     */
    public function testShouldThrowExceptionWhenLoadingANonExistentDirectory()
    {
        try {
            FactoryMuffin::loadFactories($path = __DIR__.'/thisdirectorydoesntexist');
        } catch (DirectoryNotFoundException $e) {
            $this->assertSame("The directory '$path' was not found.", $e->getMessage());
            $this->assertSame($path, $e->getPath());
            throw $e;
        }
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
