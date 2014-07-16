<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

class Integer extends Kind
{
    public function generate()
    {
        $length = (int) $this->getOption(0, 5);
        return $this->faker->randomNumber($length, true);
    }
}
