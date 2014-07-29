<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class NoDefinedFactory.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
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
     * @param string $message
     *
     * @return void
     */
    public function __construct($model, $message = null)
    {
        $this->model = $model;

        if (!$message) {
            $message = "No factory class was defined for the model of type: '$model'.";
        }

        parent::__construct($message);
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
