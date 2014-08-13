<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the delete method not found exception class.
 *
 * This is thrown when the delete method of a model does not exist. This class
 * extends MethodNotFoundException and ModelException, so you may want to try
 * to catch one of those exceptions instead, if you want to be more general.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeleteMethodNotFoundException extends MethodNotFoundException
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
            $message = "The delete method '$method' was not found on the model of type: '$model'.";
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
