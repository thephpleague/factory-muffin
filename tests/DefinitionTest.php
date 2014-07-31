<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

use League\FactoryMuffin\Exception\DirectoryNotFoundException;

/**
 * @group definition
 */
class DefinitionTest extends AbstractTestCase
{
    public function testDefine()
    {
        $user = FactoryMuffin::create('UserModelStub');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testDefineMultiple()
    {
        $user = FactoryMuffin::create('UserModelStub');

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

        // Make sure these are not the same object returned twice for some reason
        $this->assertNotEquals($users[0], $users[1]);
    }

    public function testInstance()
    {
        $user = FactoryMuffin::instance('UserModelStub');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testAttributesFor()
    {
        $attributes = FactoryMuffin::attributesFor('UserModelStub');

        $this->assertInternalType('string', $attributes['name']);
        $this->assertInternalType('boolean', $attributes['active']);
        $this->assertContains('@', $attributes['email']);
    }

    public function testFactoryLoading()
    {
        $count = count(get_included_files());

        FactoryMuffin::loadFactories(__DIR__ . '/stubs');

        $this->assertSame(1, count(get_included_files()) - $count);
    }

    public function testShouldThrowExceptionWhenLoadingANonexistentDirectory()
    {
        try {
            FactoryMuffin::loadFactories($path = __DIR__ . '/thisdirectorydoesntexist');
        } catch (DirectoryNotFoundException $e) {
            $this->assertEquals("The directory '$path' was not found.", $e->getMessage());
            $this->assertEquals($path, $e->getPath());
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
