<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::loadFactories(__DIR__.'/factories')->getGeneratorFactory()->setFakerLocale('en_GB');
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
