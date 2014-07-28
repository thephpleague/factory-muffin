<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class MethodNotFound.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class MethodNotFound extends \Exception
{
    /**
     * The model.
     *
     * @type string
     */
    private $model;

    /**
     * The method.
     *
     * @type string
     */
    private $method;

    /**
     * Create a new instance.
     *
     * @param string $model
     * @param string $method
     *
     * @return void
     */
    public function __construct($model, $method)
    {
        $this->model = $model;
        $this->method = $method;

        parent::__construct("'$model' does not have a static '$method' method.");
    }

    /**
     * Get the model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}
