<?php

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the save failed exception class.
 *
 * This is thrown when the save method of a model does not equal true in a
 * loose comparison. This class extends ModelException, so you may want to try
 * to catch that exception instead, if you want to be more general.
 *
 * @package League\FactoryMuffin\Exceptions
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
     * @param string      $model
     * @param string|null $errors
     * @param string|null $message
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
