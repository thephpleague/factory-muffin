<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::setFakerLocale('en_GB')->loadFactories(__DIR__ . '/factories');
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
