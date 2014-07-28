<?php

namespace League\FactoryMuffin\Test\Facade;

use League\FactoryMuffin\Facade\FactoryMuffin;
use League\FactoryMuffin\Test\TestCase;

class FactoryMuffinTest extends TestCase
{
    public function testDefine()
    {
        $user = FactoryMuffin::create('League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testDefineMultiple()
    {
        $user = FactoryMuffin::create('League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testInstance()
    {
        $user = FactoryMuffin::instance('League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testAttributesFor()
    {
        $attributes = FactoryMuffin::attributesFor('League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $attributes['name']);
        $this->assertInternalType('boolean', $attributes['active']);
        $this->assertContains('@', $attributes['email']);
    }
}

class User
{
    public function save()
    {
        return true;
    }
}

class Profile
{
    public function save()
    {
        return true;
    }
}
