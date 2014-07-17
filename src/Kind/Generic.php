<?php

namespace League\FactoryMuffin\Kind;

use InvalidArgumentException;
use League\FactoryMuffin\Kind;

/**
 * Class Generic
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license MIT
 * @link    https://github.com/thephpleague/factory-muffin
 */
class Generic extends Kind
{
    /**
    * We attempt to use Faker for any string passed in, if a Faker property does
    * not exist, then we just return the original string
    * @return mixed
    */
    public function generate()
    {
        // Only try and use Faker when there are no spaces in the string
        if (! is_string($this->kind) or strpos($this->kind, ' ') !== false) {
            return $this->kind;
        }

        // If it fails to call it, it must not be a real thing
        try {
            return call_user_func(array($this->faker, $this->kind));
        } catch (InvalidArgumentException $e) {

        }

        // Just return the literal string
        return $this->kind;
    }
}
