<?php

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * Class DeletingFailedException.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeletingFailedException extends Exception
{
    /**
     * The array of exceptions.
     *
     * @var \Exception[]
     */
    private $exceptions;

    /**
     * Create a new instance.
     *
     * @param \Exception[] $exceptions
     * @param string       $message
     *
     * @return void
     */
    public function __construct(array $exceptions, $message = null)
    {
        $this->exceptions = $exceptions;

        $count = count($exceptions);

        if (!$message) {
            $message = "We encountered $count problem(s) while trying to delete the saved models.";
        }

        parent::__construct($message);
    }

    /**
     * Get the array of exceptions.
     *
     * @return \Exception[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
