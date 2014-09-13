<?php

namespace League\FactoryMuffin\Facades;

/**
 * This is the factory muffin facade class.
 *
 * This is the main point of entry for using Factory Muffin. This class
 * dynamically proxies static method calls to the underlying factory.
 *
 * @see League\FactoryMuffin\FactoryMuffin
 *
 * @method static \League\FactoryMuffin\FactoryMuffin setSaveMethod(string $method) Set the method we use when saving objects.
 * @method static \League\FactoryMuffin\FactoryMuffin setDeleteMethod(string $method) Set the method we use when deleting objects.
 * @method static \League\FactoryMuffin\FactoryMuffin setCustomMaker(\Closure $maker) Set the custom maker closure.
 * @method static \League\FactoryMuffin\FactoryMuffin setCustomSetter(\Closure $setter) Set the custom setter closure.
 * @method static \League\FactoryMuffin\FactoryMuffin setCustomSaver(\Closure $saver) Set the custom saver closure.
 * @method static \League\FactoryMuffin\FactoryMuffin setCustomDeleter(\Closure $deleter) Set the custom deleter closure.
 * @method static object[] seed(int $times, string $model, array $attr = array()) Returns multiple versions of an object.
 * @method static object create(string $model, array $attr = array()) Creates and saves in db an instance of the model.
 * @method static object[] pending() Return an array of objects to be saved.
 * @method static bool isPending(object $object) Is the object going to be saved?
 * @method static object[] saved() Return an array of saved objects.
 * @method static bool isSaved(object $object) Is the object saved?
 * @method static bool isPendingOrSaved(object $object) Is the object saved or will be saved?
 * @method static \League\FactoryMuffin\FactoryMuffin deleteSaved() Call the delete method on any saved objects.
 * @method static object instance(string $model, array $attr = array()) Return an instance of the model.
 * @method static \League\FactoryMuffin\FactoryMuffin define(string $model, array $definition = array()) Define a new model factory.
 * @method static string|object generateAttr(string $kind, object|null $object = null) Generate the attributes.
 * @method static \League\FactoryMuffin\FactoryMuffin loadFactories(string|string[] $paths) Load the specified factories.
 * @method static \League\FactoryMuffin\Generators\GeneratorFactory getGeneratorFactory() Get the generator factory instance.
 *
 * @package League\FactoryMuffin\Facades
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryMuffin
{
    /**
     * The underlying factory instance.
     *
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    private static $factory;

    /**
     * Get the underlying factory instance.
     *
     * We'll always cache the instance and reuse it.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    private static function factory()
    {
        if (!self::$factory) {
            self::$factory = new \League\FactoryMuffin\FactoryMuffin();
        }

        return self::$factory;
    }

    /**
     * Reset the underlying factory instance.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public static function reset()
    {
        self::$factory = null;

        return self::factory();
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
                return self::factory()->$method();
            case 1:
                return self::factory()->$method($args[0]);
            case 2:
                return self::factory()->$method($args[0], $args[1]);
            case 3:
                return self::factory()->$method($args[0], $args[1], $args[2]);
            default:
                return call_user_func_array(array(self::factory(), $method), $args);
        }
    }
}