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
     * Get or stance FactoryMuffin obj.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    private static function fmInstance()
    {
        if (! static::$fmInstance) {
            static::$fmInstance = new \League\FactoryMuffin\FactoryMuffin;
        }

        return static::$fmInstance;
    }

    /**
     * Creates and saves in db an instance of Model with mock attributes.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return mixed Returns the model instance.
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
     * @return mixed Returns the model instance.
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
     * @return array Returns an attributes array.
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
}
