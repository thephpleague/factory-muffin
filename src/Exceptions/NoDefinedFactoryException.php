<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * Class NoDefinedFactoryException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class NoDefinedFactoryException extends ModelException
{
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
        if (!$message) {
            $message = "No factory class was defined for the model of type: '$model'.";
        }

        parent::__construct($model, $message);
    }
}
