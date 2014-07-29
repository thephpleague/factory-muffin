<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class DeletingFailed.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeletingFailed extends \Exception
{
    /**
     * The array of exceptions.
     *
     * @type \Exception[]
     */
    private $exceptions;

    /**
     * Create a new instance.
     *
     * @param \Exception[]  $exceptions
     * @param string        $message
     *
     * @return void
     */
    public function __construct(array $exceptions, $message = null)
    {
        $this->exceptions = $exceptions;

        $count = count($exceptions);

        if (!$message) {
            $message = "We encountered $count problems while trying to delete the saved models.";
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
