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

/**
 * This is the factory generator class.
 *
 * The factory generator can be useful for setting up relationships between
 * models. The factory generator will return the model id of the model you ask
 * it to generate.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 * @author Michael Bodnarchuk <davert@codeception.com>
 */
class FactoryGenerator extends EntityGenerator
{
    /**
     * Generate, and return the attribute.
     *
     * @var string[]
     */
    private static $methods = ['getKey', 'pk'];

    /**
     * The factory properties.
     *
     * @var string[]
     */
    private static $properties = ['id', '_id'];

    /**
     * Generate, and return the attribute.
     *
     * The value returned is the id of the generated model, if applicable.
     *
     * @return int|null
     */
    public function generate()
    {
        $model = parent::generate();

        return $this->getId($model);
    }

    /**
     * Get the model id.
     *
     * @param object $model The model instance.
     *
     * @return int|null
     */
    private function getId($model)
    {
        // Check to see if we can get an id via our defined methods
        foreach (self::$methods as $method) {
            if (method_exists($model, $method) && is_callable([$model, $method])) {
                return $model->$method();
            }
        }

        // Check to see if we can get an id via our defined properties
        foreach (self::$properties as $property) {
            if (isset($model->$property)) {
                return $model->$property;
            }
        }
    }

    /**
     * Get the generator prefix.
     *
     * @return string
     */
    public static function getPrefix()
    {
        return 'factory|';
    }
}
