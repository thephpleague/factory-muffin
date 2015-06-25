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

use Exception;

/**
 * This is the definition exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the many other exceptions that extend this class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DefinitionException extends Exception
{
    /**
     * The model definition name.
     *
     * @var string
     */
    private $definitionName;

    /**
     * Create a new definition exception instance.
     *
     * @param string $name    The model definition name.
     * @param string $message The exception message.
     *
     * @return void
     */
    public function __construct($name, $message)
    {
        $this->definitionName = $name;

        parent::__construct($message);
    }

    /**
     * Get the model definition name.
     *
     * @return string
     */
    public function getDefinitionName()
    {
        return $this->definitionName;
    }
}
