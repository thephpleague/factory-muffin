<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the delete failed exception class.
 *
 * This is thrown when the delete method of a model does not equal true in a
 * loose comparison. This class extends ModelException, so you may want to try
 * to catch that exception instead, if you want to be more general.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DeleteFailedException extends ModelException
{
    /**
     * Create a new delete failed exception instance.
     *
     * @param string      $class   The model class name.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $message = null)
    {
        if (!$message) {
            $message = "We could not delete the model: '$class'.";
        }

        parent::__construct($class, $message);
    }
}
