<?php

namespace League\FactoryMuffin\Test\Facade;

use League\FactoryMuffin\Facade\FactoryMuffin;

class FactoryMuffinTest extends \PHPUnit_Framework_TestCase
{
    public function testDefine()
    {
        FactoryMuffin::define('\League\FactoryMuffin\Test\Facade\User', array(
            'name' => 'string',
            'active' => 'boolean',
            'email' => 'email'
        ));

        $user = FactoryMuffin::create('\League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testDefineMultiple()
    {
        FactoryMuffin::define('\League\FactoryMuffin\Test\Facade\Profile', array(
            'profile' => 'text',
        ));

        FactoryMuffin::define('\League\FactoryMuffin\Test\Facade\User', array(
            'name' => 'string',
            'active' => 'boolean',
            'email' => 'email',
            'profile' => 'factory|\League\FactoryMuffin\Test\Facade\Profile'
        ));

        $user = FactoryMuffin::create('\League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testInstance()
    {
        FactoryMuffin::define('\League\FactoryMuffin\Test\Facade\User', array(
            'name' => 'string',
            'active' => 'boolean',
            'email' => 'email'
        ));

        $user = FactoryMuffin::instance('\League\FactoryMuffin\Test\Facade\User');

        $this->assertInternalType('string', $user->name);
        $this->assertInternalType('boolean', $user->active);
        $this->assertContains('@', $user->email);
    }

    public function testAttributesFor()
    {
        FactoryMuffin::define('\League\FactoryMuffin\Test\Facade\User', array(
            'name' => 'string',
            'active' => 'boolean',
            'email' => 'email'
        ));

        $attributes = FactoryMuffin::attributesFor('\League\FactoryMuffin\Test\Facade\User');

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
