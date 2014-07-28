<?php

namespace League\FactoryMuffin\Exception;

/**
 * Class DirectoryNotFound.
 *
 * @package League\FactoryMuffin\Exception
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
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
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
        parent::__construct("The directory '$path' was not found.");
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
