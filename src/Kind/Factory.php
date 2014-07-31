<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Facade\FactoryMuffin;
use League\FactoryMuffin\Kind;

/**
 * Class Factory.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Factory extends Kind
{
    /**
     * Generate, and return the attribute.
     *
     * @type string[]
     */
    private $methods = array('getKey', 'pk');

    /**
     * The factory properties.
     *
     * @type string[]
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
