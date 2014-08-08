<?php

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * This is the class not found exception class
 *
 * This is thrown when we try to create an object, but the class
 * defined is not found.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class ClassNotFoundException extends Exception
{
    /**
     * Create a new instance.
     *
     * @param string      $class
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($class)
    {
        $message = "Class cannot be found when creating Factory: '$class'.";
        parent::__construct($message);
    }
}
