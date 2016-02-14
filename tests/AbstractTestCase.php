<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;

/**
 * This is abstract test case class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var FactoryMuffin
     */
    protected static $fm;

    public static function setupBeforeClass()
    {
        static::$fm = new FactoryMuffin();
        static::$fm->loadFactories(__DIR__.'/factories');
        Faker::setLocale('en_GB');
    }

    public static function tearDownAfterClass()
    {
        static::$fm->deleteSaved();
        static::$fm = new FactoryMuffin();
    }

    protected function reload()
    {
        static::tearDownAfterClass();
        static::setupBeforeClass();
    }
}
