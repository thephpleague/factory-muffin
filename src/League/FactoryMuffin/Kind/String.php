<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

class String extends Kind
{
    public function generate()
    {
        $length = $this->getOption(0, 10);
        return $this->faker->lexify(str_repeat('?', $length));
    }
}
