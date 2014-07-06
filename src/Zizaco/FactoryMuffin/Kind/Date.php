<?php

namespace Zizaco\FactoryMuffin\Kind;

use Zizaco\FactoryMuffin\Kind;

class Date extends Kind
{
    public function generate()
    {
        $format = $this->getOption(0, 'r');

        return $this->faker->date($format);
    }
}
