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

}

class User
{
  public function save()
  {
    return true;
  }
}
