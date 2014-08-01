<?php

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * Class Factory.
 *
 * @package League\FactoryMuffin\Generator
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Factory extends Base
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
     * @return int
     */
    public function generate()
    {
        $factory = substr($this->kind, 8);

        if (FactoryMuffin::isSaved($this->object)) {
            $object = FactoryMuffin::create($factory);
        } else {
            $object = FactoryMuffin::instance($factory);
        }

        return $this->getId($object);
    }

    /**
     * Get the model id.
     *
     * @param object $object The model instance.
     *
     * @return int
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
