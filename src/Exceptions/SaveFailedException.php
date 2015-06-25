<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FactoryMuffin\Exceptions;

/**
 * This is the save failed exception class.
 *
 * This is thrown when the save method of a model does not equal true in a
 * loose comparison. This class extends ModelException, so you may want to try
 * to catch that exception instead, if you want to be more general.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class SaveFailedException extends ModelException
{
    /**
     * The validation errors.
     *
     * @var string|null
     */
    private $validationErrors;

    /**
     * Create a new save failed exception instance.
     *
     * @param string      $class   The model class name.
     * @param string|null $errors  The validation errors.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $errors = null, $message = null)
    {
        $errors = $this->formatErrors($errors);

        $this->validationErrors = $errors;

        if (!$message) {
            if ($errors) {
                $message = "$errors We could not save the model: '$class'.";
            } else {
                $message = "We could not save the model: '$class'.";
            }
        }

        parent::__construct($class, $message);
    }

    /**
     * Format the given errors correctly.
     *
     * We're stripping any trailing whitespace, and ensuring they end in a
     * punctuation character. If null is passed, then null is returned also.
     *
     * @param string|null $errors
     *
     * @return string|null
     */
    private function formatErrors($errors)
    {
        if ($errors) {
            $errors = trim($errors);

            if (in_array(substr($errors, -1), ['.', '!', '?'], true)) {
                return $errors;
            }

            return $errors.'.';
        }
    }

    /**
     * Get the validation errors.
     *
     * @return string|null
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
