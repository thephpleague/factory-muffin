<?php

namespace League\FactoryMuffin\Facades;

/**
 * This is the faker facade class.
 *
 * This class dynamically proxies static method calls to the underlying faker.
 *
 * @see League\FactoryMuffin\Faker
 *
 * @package League\FactoryMuffin\Facades
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/faker-muffin/blob/master/LICENSE> MIT
 */
class Faker
{
    /**
     * The underlying faker instance.
     *
     * @var \League\FactoryMuffin\Faker
     */
    private static $faker;

    /**
     * Get the underlying faker instance.
     *
     * We'll always cache the instance and reuse it.
     *
     * @return \League\FactoryMuffin\Faker
     */
    private static function faker()
    {
        if (!self::$faker) {
            self::$faker = new \League\FactoryMuffin\Faker();
        }

        return self::$faker;
    }

    /**
     * Reset the underlying faker instance.
     *
     * @return \League\FactoryMuffin\Faker
     */
    public static function reset()
    {
        self::$faker = null;

        return self::faker();
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @codeCoverageIgnore
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        switch (count($args)) {
            case 0:
                return self::faker()->$method();
            case 1:
                return self::faker()->$method($args[0]);
            case 2:
                return self::faker()->$method($args[0], $args[1]);
            case 3:
                return self::faker()->$method($args[0], $args[1], $args[2]);
            default:
                return call_user_func_array(array(self::faker(), $method), $args);
        }
    }
}
