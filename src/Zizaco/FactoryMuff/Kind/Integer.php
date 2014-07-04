<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Integer extends Kind
{
    public function generate()
    {
        $length = (int) $this->getOption(0, 5);
        return $this->faker->randomNumber($length, true);
    }
}
