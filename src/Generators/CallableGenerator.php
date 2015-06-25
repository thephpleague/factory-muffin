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

use Closure;
use League\FactoryMuffin\FactoryMuffin;

/**
 * This is the callable generator class.
 *
 * The callable generator can be used if you want a more custom solution.
 * Whatever you return from the callable you write will be set as the attribute.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
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
    protected $model;

    /**
     * The factory muffin instance.
     *
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    protected $factoryMuffin;

    /**
     * Create a new callable generator instance.
     *
     * @param callable                            $kind          The kind of attribute.
     * @param object                              $model         The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return void
     */
    public function __construct(callable $kind, $model, FactoryMuffin $factoryMuffin)
    {
        if ($kind instanceof Closure) {
            $kind = $kind->bindTo($factoryMuffin);
        }

        $this->kind = $kind;
        $this->model = $model;
        $this->factoryMuffin = $factoryMuffin;
    }

    /**
     * Generate, and return the attribute.
     *
     * The value returned is the result of calling the callable.
     *
     * @return mixed
     */
    public function generate()
    {
        $saved = $this->factoryMuffin->isPendingOrSaved($this->model);

        return call_user_func($this->kind, $this->model, $saved);
    }
}
