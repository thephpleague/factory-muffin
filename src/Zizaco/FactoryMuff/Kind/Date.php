<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Date extends Kind
{
    public function generate()
    {
        $format = $this->getOption(0, 'r');

        return $this->faker->date($format);
    }
}
