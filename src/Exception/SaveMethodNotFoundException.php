<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class SaveMethodNotFoundException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class SaveMethodNotFoundException extends MethodNotFoundException
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
     * @param string $object
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
