<?php

namespace League\FactoryMuffin\Facade;

/**
 * Class FactoryMuffin.
 *
 * This is the optional, sleeker FactoryMuffin facade accessor.
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
            self::$fmInstance = new \League\FactoryMuffin\FactoryMuffin;
        }

        return self::$fmInstance;
    }

    /**
     * Creates and saves in db an instance of Model with mock attributes.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return object
     */
    public static function create($model, $attr = array())
    {
        return static::fmInstance()->create($model, $attr);
    }

    /**
     * Define a new model factory.
     *
     * @param string $model      Model class name.
     * @param array  $definition Array with definition of attributes.
     *
     * @return void
     */
    public static function define($model, array $definition = array())
    {
        static::fmInstance()->define($model, $definition);
    }

    /**
     * Return an instance of the model, which is not saved in the database.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return object
     */
    public static function instance($model, $attr = array())
    {
        return static::fmInstance()->instance($model, $attr);
    }

    /**
     * Returns an array of mock attributes for the specified model.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return array
     */
    public static function attributesFor($model, $attr = array())
    {
        return static::fmInstance()->attributesFor($model, $attr);
    }

    /**
     * Pass through method to generate attributes for model.
     *
     * @param string $model Model class name.
     *
     * @return mixed
     */
    public static function generateAttr($model)
    {
        return static::fmInstance()->generateAttr($model);
    }

    /**
     * Returns an array of saved objects.
     *
     * @return object[]
     */
    public static function saved()
    {
        return static::fmInstance()->saved();
    }

    /**
     * Call delete on any saved objects.
     *
     * @return mixed
     */
    public static function deleteSaved()
    {
        return static::fmInstance()->deleteSaved();
    }

    /**
     * Sets the method used to delete objects.
     *
     * @param string $method
     *
     * @return void
     */
    public static function setDeleteMethod($method)
    {
        return static::fmInstance()->setDeleteMethod($method);
    }

    /**
     * Sets the method used to save objects.
     *
     * @param string $method
     *
     * @return void
     */
    public static function setSaveMethod($method)
    {
        return static::fmInstance()->setSaveMethod($method);
    }

    /**
     * Load the specified factories.
     *
     * @param string|string[] $paths
     *
     * @return void
     */
    public static function loadFactories($paths)
    {
        return static::fmInstance()->loadFactories($paths);
    }
}
