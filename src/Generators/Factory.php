<?php

namespace League\FactoryMuffin\Generators;

/**
 * This is the factory generator class.
 *
 * The factory generator can be useful for setting up relationships between
 * models. The factory generator will return the model id of the model you ask
 * it to generate. Please note that class is not be considered part of the
 * public api, and should only be used internally by Factory Muffin.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
final class Factory extends Base
{
    /**
     * Generate, and return the attribute.
     *
     * @var string[]
     */
    private $methods = array('getKey', 'pk');

    /**
     * The factory properties.
     *
     * @var string[]
     */
    private $properties = array('id', '_id');

    /**
     * Return generated data.
     *
     * @return int|null
     */
    public function generate()
    {
        $model = substr($this->kind, 8);

        $object = $this->factory($model);

        return $this->getId($object);
    }

    /**
     * Get the model id.
     *
     * @param object $object The model instance.
     *
     * @return int|null
     */
    private function getId($object)
    {
        // Check to see if we can get an ID via our defined methods
        foreach ($this->methods as $method) {
            if (method_exists($object, $method)) {
                return $object->$method();
            }
        }

        // Check to see if we can get an ID via our defined methods
        foreach ($this->properties as $property) {
            if (isset($object->$property)) {
                return $object->$property;
            }
        }
    }
}
