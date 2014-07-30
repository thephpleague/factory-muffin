<?php

namespace League\FactoryMuffin\Exception;

use Exception;

/**
 * Class SaveFailedException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class SaveFailedException extends Exception
{
    /**
     * The model.
     *
     * @type string
     */
    private $model;

    /**
     * The errors.
     *
     * @type string
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
        $this->model = $model;
        $this->errors = $errors;

        if (!$message) {
            if ($errors) {
                $message = "$errors We could not save the model of type: '$model'.";
            } else {
                $message = "We could not save the model of type: '$model'.";
            }
        }

        parent::__construct($message);
    }

    /**
     * Get the model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
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