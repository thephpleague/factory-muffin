<?php

namespace League\FactoryMuffin\Test\Facade;

use League\FactoryMuffin\Facade\FactoryMuffin;

class FactoryMuffinTest extends \League\FactoryMuffin\Test\TestCase
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

  public function testSetPath()
  {
    $attributes = FactoryMuffin::attributesFor('User1');
    var_dump($attributes);
  }
}

class User
{
  public function save()
  {
    return true;
  }
}
