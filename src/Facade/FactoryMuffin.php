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
 * @method static void setSaveMethod(string $method) Set the method we use when saving objects.
 * @method static void setDeleteMethod(string $method) Set the method we use when deleting objects.
 * @method static object[] seed(string $model, int $times = 1, array $attr = array()) Returns multiple versions of an object.
 * @method static object create(string $model, array $attr = array()) Creates and saves in db an instance of the model.
 * @method static object[] saved() Return an array of saved objects.
 * @method static bool isSaved(object $object) Is the object saved?
 * @method static void deleteSaved() Call the delete method on any saved objects.
 * @method static object instance(string $model, array $attr = array()) Return an instance of the model.
 * @method static array attributesFor(object $object, array $attr = array()) Returns the mock attributes for the model.
 * @method static void define(string $model, array $definition = array()) Define a new model factory.
 * @method static string|object generateAttr(string $kind, object $object = null) Generate the attributes.
 * @method static void loadFactories(string|string[] $paths) Load the specified factories.
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
     * @var \League\FactoryMuffin\FactoryMuffin
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
     * @codeCoverageIgnore
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
