<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class DeleteMethodNotFound.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeleteMethodNotFound extends MethodNotFound
{
    /**
     * The model instance.
     *
     * @type object
     */
    private $object;

    /**
     * Create a new instance.
     *
     * @param string $model
     * @param string $method
     * @param string $message
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
