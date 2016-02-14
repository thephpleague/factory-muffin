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
 * This is the entity generator class.
 *
 * The entity generator can be useful for setting up relationships between
 * models. The entity generator will create and return the generated model.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 * @author Michael Bodnarchuk <davert@codeception.com>
 */
class EntityGenerator implements GeneratorInterface, PrefixInterface
{
    /**
     * The kind of attribute that will be generated.
     *
     * @var string
     */
    private $kind;

    /**
     * The model instance.
     *
     * @var object
     */
    private $model;

    /**
     * The factory muffin instance.
     *
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    private $factoryMuffin;

    /**
     * Create a new factory generator instance.
     *
     * @param string                              $kind          The kind of attribute.
     * @param object                              $model         The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return void
     */
    public function __construct($kind, $model, FactoryMuffin $factoryMuffin)
    {
        $this->kind = $kind;
        $this->model = $model;
        $this->factoryMuffin = $factoryMuffin;
    }

    /**
     * Generate, and return the attribute.
     *
     * The value returned is the id of the generated model, if applicable.
     *
     * @return object
     */
    public function generate()
    {
        $name = substr($this->kind, strlen(static::getPrefix()));

        return $this->factory($name);
    }

    /**
     * Create an instance of the model.
     *
     * This model will be automatically saved to the database if the model we
     * are generating it for has been saved (the create function was used).
     *
     * @param string $name The model definition name.
     *
     * @return object
     */
    private function factory($name)
    {
        if ($this->factoryMuffin->isPendingOrSaved($this->model)) {
            return $this->factoryMuffin->create($name);
        }

        return $this->factoryMuffin->instance($name);
    }

    /**
     * Get the generator prefix.
     *
     * @return string
     */
    public static function getPrefix()
    {
        return 'entity|';
    }
}
