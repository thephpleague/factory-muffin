<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * Class MethodNotFoundException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
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
     * @param string $model
     * @param string $method
     * @param string $message
     *
     * @return void
     */
    public function __construct($model, $method, $message = null)
    {
        $this->method = $method;

        if (!$message) {
            $message = "The static method '$method' was not found on the model of type: '$model'.";
        }

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
