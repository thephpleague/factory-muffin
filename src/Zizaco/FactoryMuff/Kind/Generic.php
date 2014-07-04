<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

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
