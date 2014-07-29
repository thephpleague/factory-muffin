<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class DeleteMethodNotFound.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeleteMethodNotFound extends \Exception
{
    /**
     * The model.
     *
     * @type object
     */
    private $model;

    /**
     * The model name.
     *
     * @type string
     */
    private $model_name;

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
        $this->model_name = get_class($model);

        parent::__construct("The delete method '$method' was not found on the model: '$this->model_name'.");
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

    /**
     * Get the model name.
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->model_name;
    }
}
