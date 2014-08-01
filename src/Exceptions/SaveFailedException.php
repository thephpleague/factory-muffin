<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * Class SaveFailedException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class SaveFailedException extends ModelException
{
    /**
     * The errors.
     *
     * @var string
     */
    private $errors;

    /**
     * Create a new instance.
     *
     * @param string $model
     * @param string $errors
     * @param string $message
     *
     * @return void
     */
    public function __construct($model, $errors = null, $message = null)
    {
        $this->errors = $errors;

        if (!$message) {
            if ($errors) {
                $message = "$errors We could not save the model of type: '$model'.";
            } else {
                $message = "We could not save the model of type: '$model'.";
            }
        }

        parent::__construct($model, $message);
    }

    /**
     * Get the errors.
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
