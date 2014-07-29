<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

class FacadeTest extends AbstractTestCase
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
