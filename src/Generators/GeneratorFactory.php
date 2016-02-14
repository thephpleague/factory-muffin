<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\FactoryMuffin;

/**
 * This is the generator factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 * @author Michael Bodnarchuk <davert@codeception.com>
 */
class GeneratorFactory
{
    /**
     * Automatically generate the attribute we want.
     *
     * @param string|callable                     $kind          The kind of attribute.
     * @param object                              $model         The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return mixed
     */
    public function generate($kind, $model, FactoryMuffin $factoryMuffin)
    {
        $generator = $this->make($kind, $model, $factoryMuffin);

        if ($generator) {
            return $generator->generate();
        }

        return $kind;
    }

    /**
     * Automatically make the generator class we need.
     *
     * @param string|callable                     $kind          The kind of attribute.
     * @param object                              $model         The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return \League\FactoryMuffin\Generators\GeneratorInterface|null
     */
    public function make($kind, $model, FactoryMuffin $factoryMuffin)
    {
        if (is_callable($kind)) {
            return new CallableGenerator($kind, $model, $factoryMuffin);
        }

        if (is_string($kind) && strpos($kind, EntityGenerator::getPrefix()) === 0) {
            return new EntityGenerator($kind, $model, $factoryMuffin);
        }

        if (is_string($kind) && strpos($kind, FactoryGenerator::getPrefix()) === 0) {
            return new FactoryGenerator($kind, $model, $factoryMuffin);
        }
    }
}
