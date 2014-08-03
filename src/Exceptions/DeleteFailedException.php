<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * Class DeleteFailedException.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeleteFailedException extends ModelException
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
            $message = "We could not delete the model of type: '$model'.";
        }

        parent::__construct($model, $message);
    }
}
