<?php

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\FactoryMuffin;

/**
 * This is the closure generator class.
 *
 * The closure generator can be used if you want a more custom solution.
 * Whatever you return from the closure you write will be set as the attribute.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class CallableGenerator implements GeneratorInterface
{
    /**
     * The kind of attribute that will be generated.
     *
     * @var callable
     */
    protected $kind;

    /**
     * The model instance.
     *
     * @var object
     */
    protected $object;

    /**
     * The factory muffin instance.
     *
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    protected $factoryMuffin;

    /**
     * Create a new instance.
     *
     * @param string $kind   The kind of attribute.
     * @param object $object The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return void
     */
    public function __construct(callable $kind, $object, FactoryMuffin $factoryMuffin)
    {
        $this->kind = $kind;
        $this->object = $object;
        $this->factoryMuffin = $factoryMuffin;
    }

    /**
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    public function generate()
    {
        $kind = $this->kind;

        $saved = $this->factoryMuffin->isPendingOrSaved($this->object);

        return call_user_func($kind, $this->object, $saved);
    }
}
