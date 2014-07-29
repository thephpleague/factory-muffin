<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class DirectoryNotFound.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DirectoryNotFound extends \Exception
{
    /**
     * The path.
     *
     * @type string
     */
    private $path;

    /**
     * Create a new instance.
     *
     * @param string $path
     * @param string $message
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
