<?php

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * This is the directory not found exception class.
 *
 * This is thrown if you try to load factory definitions from a directory that
 * doesn't exit.
 *
 * @package League\FactoryMuffin\Exceptions
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DirectoryNotFoundException extends Exception
{
    /**
     * The path.
     *
     * @var string
     */
    private $path;

    /**
     * Create a new instance.
     *
     * @param string      $path
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($path, $message = null)
    {
        $this->path = $path;

        if (!$message) {
            $message = "The directory '$path' was not found.";
        }

        parent::__construct($message);
    }

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
