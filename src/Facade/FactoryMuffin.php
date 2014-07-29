<?php

namespace League\FactoryMuffin\Facade;

/**
 * Class FactoryMuffin.
 *
 * This is the optional, sleeker FactoryMuffin facade accessor.
 * All methods available on the main class are available here, but statically.
 *
 * @see League\FactoryMuffin\FactoryMuffin
 *
 * @package League\FactoryMuffin\Facades
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryMuffin
{
    /**
     * The underline FactoryMuffin instance.
     *
     * @type \League\FactoryMuffin\FactoryMuffin
     */
    private static $fmInstance;

    /**
     * Get the underline FactoryMuffin instance.
     *
     * We'll always cache the instance and reuse it.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    protected static function fmInstance()
    {
        if (!self::$fmInstance) {
            self::$fmInstance = new \League\FactoryMuffin\FactoryMuffin();
        }

        return self::$fmInstance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::fmInstance();

        switch (count($args)) {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($args[0]);
            case 2:
                return $instance->$method($args[0], $args[1]);
            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);
            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }
}
