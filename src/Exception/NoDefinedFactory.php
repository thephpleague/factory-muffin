<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class NoDefinedFactory.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class NoDefinedFactory extends \Exception
{
    /**
     * The model.
     *
     * @type string
     */
    private $model;

    /**
     * Create a new instance.
     *
     * @param string $model
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        parent::__construct("No factory class was defined for the model of type: '$model'.");
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
}
