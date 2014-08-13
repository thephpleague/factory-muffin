<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the save method not found exception class.
 *
 * This is thrown when the save method of a model does not exist. This class
 * extends MethodNotFoundException and ModelException, so you may want to try
 * to catch one of those exceptions instead, if you want to be more general.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class SaveMethodNotFoundException extends MethodNotFoundException
{
    /**
     * The model instance.
     *
     * @var object
     */
    private $object;

    /**
     * Create a new instance.
     *
     * @param object      $object
     * @param string      $method
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($object, $method, $message = null)
    {
        $this->object = $object;

        $model = get_class($object);

        if (!$message) {
            $message = "The save method '$method' was not found on the model of type: '$model'.";
        }

        parent::__construct($model, $method, $message);
    }

    /**
     * Get the model instance.
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
}
