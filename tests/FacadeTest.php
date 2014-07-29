<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

class FacadeTest extends AbstractTestCase
{
    public function testDefine()
    {
        $user = FactoryMuffin::create('UserStub');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testDefineMultiple()
    {
        $user = FactoryMuffin::create('UserStub');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testInstance()
    {
        $user = FactoryMuffin::instance('UserStub');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testAttributesFor()
    {
        $attributes = FactoryMuffin::attributesFor('UserStub');

        $this->assertInternalType('string', $attributes['name']);
        $this->assertInternalType('boolean', $attributes['active']);
        $this->assertContains('@', $attributes['email']);
    }
}

class UserStub
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

class ProfileStub
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
