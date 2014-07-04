<?php

namespace Zizaco\FactoryMuff\Kind;

use InvalidArgumentException;
use Zizaco\FactoryMuff\Kind;

class None extends Kind
{
    public function generate()
    {
        try {
            return call_user_func(array($this->faker, $this->kind));
        } catch (InvalidArgumentException $e) {
            return $this->kind;
        }
    }
}
