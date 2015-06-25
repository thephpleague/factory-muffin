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

use Exception;

/**
 * This is the deleting failed exception class.
 *
 * This is thrown if one or more models threw an exception when we tried to
 * delete them. It's important to note that this exception will only be thrown
 * after we've attempted to delete all the saved models. You may access each
 * underlying exception, in the order they were thrown during the whole
 * process, by calling getExceptions to return an array of exceptions.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DeletingFailedException extends Exception
{
    /**
     * The array of caught exceptions.
     *
     * @var \Exception[]
     */
    private $exceptions;

    /**
     * Create a new deleting failed exception instance.
     *
     * @param \Exception[] $exceptions The caught exceptions.
     * @param string|null  $message    The exception message.
     *
     * @return void
     */
    public function __construct(array $exceptions, $message = null)
    {
        $this->exceptions = $exceptions;

        if (!$message) {
            $count = count($exceptions);
            $problems = $this->plural('problem', $count);
            $message = "We encountered $count $problems while trying to delete the saved models.";
        }

        parent::__construct($message);
    }

    /**
     * Get the plural form of a word if required by the "count".
     *
     * @param string $word
     * @param int    $count
     *
     * @return string
     */
    private function plural($word, $count)
    {
        if ($count === 1) {
            return $word;
        }

        return $word.'s';
    }

    /**
     * Get the array of caught exceptions.
     *
     * @return \Exception[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
