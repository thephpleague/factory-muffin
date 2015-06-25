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
 * This is the model exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the many other exceptions that extend this class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ModelException extends Exception
{
    /**
     * The model class name.
     *
     * @var string
     */
    private $modelClass;

    /**
     * Create a new model exception instance.
     *
     * @param string $class   The model class name.
     * @param string $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $message)
    {
        $this->modelClass = $class;

        parent::__construct($message);
    }

    /**
     * Get the model class name.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }
}
