<?php

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * This is the model not found exception class
 *
 * This is thrown when we try to create an object, but the model (class)
 * defined is not found.
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
     * @param string      $class
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($model, $message = null)
    {
        if (!$message) {
            $message = "Class cannot be found when creating Factory: '$model'.";
        }

        parent::__construct($model, $message);
    }
}
