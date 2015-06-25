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
 * This is the method not found exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the 2 other exceptions that extend this class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MethodNotFoundException extends ModelException
{
    /**
     * The method name.
     *
     * @var string
     */
    private $methodName;

    /**
     * Create a new method not found exception instance.
     *
     * @param string $class   The model class name.
     * @param string $method  The method name.
     * @param string $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $method, $message)
    {
        $this->methodName = $method;

        parent::__construct($class, $message);
    }

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }
}
