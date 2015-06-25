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
 * This is the directory not found exception class.
 *
 * This is thrown if you try to load model definitions from a directory that
 * doesn't exit.
 *
 * @author Graham Campbell <graham@alt-three.com>
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
     * Create a new directory not found exception instance.
     *
     * @param string      $path    The directory path.
     * @param string|null $message The exception message.
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
