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
 * This is the model not found exception class.
 *
 * This is thrown when we try to create an object, but the model class defined
 * was not found. This class extends ModelException, so you may want to try to
 * catch that exception instead, if you want to be more general.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
class ModelNotFoundException extends ModelException
{
    /**
     * Create a new model not found exception instance.
     *
     * @param string      $class   The model class name.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $message = null)
    {
        if (!$message) {
            $message = "The model class '$class' is undefined.";
        }

        parent::__construct($class, $message);
    }
}
