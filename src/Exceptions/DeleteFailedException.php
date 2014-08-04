<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the delete failed exception class.
 *
 * This is thrown when the delete method of a model does not equal true in a
 * loose comparison. This class extends ModelException, so you may want to try
 * to catch that exception instead, if you want to be more general.
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
     * @param string      $model
     * @param string|null $message
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
