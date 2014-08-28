<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the method not found exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the 2 other exceptions that extend this class.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class MethodNotFoundException extends ModelException
{
    /**
     * The method.
     *
     * @var string
     */
    private $method;

    /**
     * Create a new instance.
     *
     * @param string  $model
     * @param string  $method
     * @param string $message
     *
     * @return void
     */
    public function __construct($model, $method, $message)
    {
        $this->method = $method;

        parent::__construct($model, $message);
    }

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}
