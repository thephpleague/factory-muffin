<?php

namespace League\FactoryMuffin\Test;

use League\FactoryMuffin\Facade\FactoryMuffin;

class TestCase extends \PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  {
    FactoryMuffin::setPath(dirname(__DIR__) . '/defintions');
  }
}
