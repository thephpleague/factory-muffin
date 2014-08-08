<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the model not found exception class.
 *
 * This is thrown when we try to create an object, but the model class defined
 * is not found. This class extends ModelException, so you may want to try to
 * catch that exception instead, if you want to be more general.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class ModelNotFoundException extends ModelException
{
    /**
     * Create a new instance.
     *
     * @param string      $model
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($model, $message = null)
    {
        if (!$message) {
            $message = "No class was defined for the model of type: '$model'.";
        }

        parent::__construct($model, $message);
    }
}
