<?php

namespace League\FactoryMuffin;

use League\FactoryMuffin\Exception\NoDefinedFactory;
use League\FactoryMuffin\Exception\Save;

/**
 * Class FactoryMuffin.
 *
 * @package League\FactoryMuffin
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryMuffin
{
    /**
     * The array of factories.
     *
     * @type array
     */
    private $factories = array();

    /**
     * Creates and saves in db an instance of Model with mock attributes.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @throws \League\FactoryMuffin\Exception\Save
     *
     * @return object
     */
    public function create($model, $attr = array())
    {
        $obj = $this->instance($model, $attr);
        $result = $this->save($obj);
        if (!$result) {

            $message = '';

            if (isset($obj->validationErrors)) {
                if ($obj->validationErrors) {
                    $message = $obj->validationErrors.' - ';
                }
            }

            throw new Save($message.'Could not save the model of type: '.$model);
        }

        return $obj;
    }

    /**
     * Save our object to the DB, and keep track of it
     * @param  object $object
     * @return mixed
     */
    public function save($object)
    {
        $result = $object->save();

        return $result;
    }

    /**
     * Return an instance of the model, which is not saved in the database.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return object
     */
    public function instance($model, $attr = array())
    {
        // Get the factory attributes for that model
        $attr_array = $this->attributesFor($model, $attr);

        // Create, set, save and return instance
        $obj = new $model();

        foreach ($attr_array as $attr => $value) {
            $obj->$attr = $value;
        }

        return $obj;
    }

    /**
     * Returns an array of mock attributes for the specified model.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return array
     */
    public function attributesFor($model, $attr = array())
    {
        $factory_attrs = $this->getFactoryAttrs($model);

        // Prepare attributes
        foreach ($factory_attrs as $key => $kind) {
            if (! isset($attr[$key])) {
                $attr[$key] = $this->generateAttr($kind, $model);
            }
        }

        return $attr;
    }

    /**
     * Define a new model factory.
     *
     * @param string $model      Model class name.
     * @param array  $definition Array with definition of attributes.
     *
     * @return void
     */
    public function define($model, array $definition = array())
    {
        $this->factories[$model] = $definition;
    }

    /**
     * Get factory attributes.
     *
     * @param string $model Model class name.
     *
     * @throws \League\FactoryMuffin\Exception\NoDefinedFactory
     *
     * @return mixed
     */
    private function getFactoryAttrs($model)
    {
        if (isset($this->factories[$model])) {
            return $this->factories[$model];
        }

        throw new NoDefinedFactory('Factory not defined for class: ' . $model);
    }

    /**
     * Generate the attributes and return a string, or an instance of the model.
     *
     * @param string $kind  The kind of attribute that will be generate.
     * @param string $model The name of the model class.
     *
     * @return string|object
     */
    public function generateAttr($kind, $model = null)
    {
        $kind = Kind::detect($kind, $model);

        return $kind->generate();
    }
}
