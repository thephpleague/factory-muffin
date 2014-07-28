<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class Save.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Save extends \Exception
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
     *
     * @return void
     */
    public function __construct($model, $errors = null)
    {
        $this->model = $model;
        $this->errors = $errors;

        if ($errors) {
            parent::__construct("$errors We could not save the model of type: '$model'.");
        } else {
            parent::__construct("We could not save the model of type: '$model'.");
        }
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
