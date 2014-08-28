<?php

namespace League\FactoryMuffin\Generators;

use Exception;

/**
 * This is the generator factory class.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class GeneratorFactory
{
    /**
     * Automatically generate the attribute we want.
     *
     * @param string|callable $kind   The kind of attribute.
     * @param object|null     $object The model instance.
     *
     * @return \League\FactoryMuffin\Generators\GeneratorInterface
     */
    public function generate($kind, $object = null)
    {
        if ($generator = $this->make($kind, $object)) {
            return $generator->generate();
        }

        return $kind;
    }

    /**
     * Automatically make the generator class we need.
     *
     * @param string|callable $kind   The kind of attribute.
     * @param object|null     $object The model instance.
     *
     * @return \League\FactoryMuffin\Generators\GeneratorInterface|null
     */
    public function make($kind, $object = null)
    {
        if (is_callable($kind)) {
            return new CallableGenerator($kind, $object);
        }

        if (strpos($kind, 'factory|') !== false) {
            return new FactoryGenerator($kind, $object);
        }
    }
}
