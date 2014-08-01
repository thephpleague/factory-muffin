<?php

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * Class ModelException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class ModelException extends Exception
{
    /**
     * The model.
     *
     * @var string
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
    public function __construct($model, $message)
    {
        $this->model = $model;

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
