<?php

use League\FactoryMuffin\Facade as FactoryMuffin;
use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;

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

    /**
     * @expectedException League\FactoryMuffin\Exceptions\ModelNotFoundException
     * @expectedExceptionMessage Class cannot be found when creating Factory: 'notAClass'
     */
    public function testModelNotFound()
    {
        FactoryMuffin::create('notAClass');
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
        $this->assertEquals('false', $user->active);
        $this->assertContains('@', $user->email);
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

    public function testAttributesFor()
    {
        $object = new UserModelStub();
        $attributes = FactoryMuffin::attributesFor($object);

        $this->assertInternalType('string', $attributes['name']);
        $this->assertInternalType('boolean', $attributes['active']);
        $this->assertContains('@', $attributes['email']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testFactoryLoading()
    {
        $count = count(get_included_files());

        $return = FactoryMuffin::loadFactories(__DIR__ . '/stubs');

        $this->assertSame(1, count(get_included_files()) - $count);
        $this->assertInstanceOf('League\FactoryMuffin\Factory', $return);
    }

    /**
     * @expectedException \League\FactoryMuffin\Exceptions\DirectoryNotFoundException
     */
    public function testShouldThrowExceptionWhenLoadingANonexistentDirectory()
    {
        try {
            FactoryMuffin::loadFactories($path = __DIR__ . '/thisdirectorydoesntexist');
        } catch (DirectoryNotFoundException $e) {
            $this->assertEquals("The directory '$path' was not found.", $e->getMessage());
            $this->assertEquals($path, $e->getPath());
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
