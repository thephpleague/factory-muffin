<?php

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * This is the factory generator class.
 *
 * The factory generator can be useful for setting up relationships between
 * models. The factory generator will return the model id of the model you ask
 * it to generate.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryGenerator implements GeneratorInterface
{
    /**
     * The kind of attribute that will be generated.
     *
     * @var string
     */
    protected $kind;

    /**
     * The model instance.
     *
     * @var object
     */
    protected $object;

    /**
     * Initialise our Generator.
     *
     * @param string $kind   The kind of attribute
     * @param object $object The model instance.
     *
     * @return void
     */
    public function __construct($kind, $object)
    {
        $this->kind = $kind;
        $this->object = $object;
    }

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
     * Create an instance of the model.
     *
     * This model will be automatically saved to the database if the model we
     * are generating it for has been saved (the create function was used).
     *
     * @param string $model Model class name.
     *
     * @return object
     */
    private function factory($model)
    {
        if (FactoryMuffin::isPendingOrSaved($this->object)) {
            return FactoryMuffin::create($model);
        }

        return FactoryMuffin::instance($model);
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
