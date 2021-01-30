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
     * @var string|string[]|null
     */
    private $validationErrors;

    /**
     * Create a new save failed exception instance.
     *
     * @param string               $class   The model class name.
     * @param string|string[]|null $errors  The validation errors.
     * @param string|null          $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $errors = null, $message = null)
    {
        $errors = self::formatErrors($errors);

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
     * @param string|string[]|null $errors
     *
     * @return string|null
     */
    private static function formatErrors($errors)
    {
        if (!$errors) {
            return null;
        }

        if (is_array($errors)) {
            $errors = array_map(function ($error) {
                return self::formatError($error);
            }, $errors);

            return implode(' ', $errors);
        }

        return self::formatError($errors);
    }

    /**
     * Format the given error.
     *
     * @param string $error
     *
     * @return string
     */
    private static function formatError($error)
    {
        $error = trim($error);

        if (in_array(substr($error, -1), ['.', '!', '?'], true)) {
            return $error;
        }

        return $error.'.';
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
