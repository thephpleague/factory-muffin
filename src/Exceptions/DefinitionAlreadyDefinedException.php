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
 * This is the definition already defined exception class.
 *
 * This is thrown when you try register a model definition that has already
 * been defined. This class extends DefinitionException, so you may want to try
 * to catch that exception instead, if you want to be more general.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DefinitionAlreadyDefinedException extends DefinitionException
{
    /**
     * Create a new definition already defined exception instance.
     *
     * @param string      $name    The model definition name.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($name, $message = null)
    {
        if (!$message) {
            $message = "The model definition '$name' has already been defined.";
        }

        parent::__construct($name, $message);
    }
}
