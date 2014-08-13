<?php

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * This is the model exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the many other exceptions that extend this class.
 *
 * @package League\FactoryMuffin\Exceptions
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
