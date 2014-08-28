<?php

use League\FactoryMuffin\Facades\FactoryMuffin;
use League\FactoryMuffin\Facades\Faker;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        Faker::setLocale('en_GB');
        FactoryMuffin::loadFactories(__DIR__.'/factories');
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::deleteSaved();
        FactoryMuffin::reset();
    }

    protected function reload()
    {
        static::tearDownAfterClass();
        static::setupBeforeClass();
    }
}
