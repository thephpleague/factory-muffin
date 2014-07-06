<?php

namespace Zizaco\FactoryMuffin\Kind;

use Zizaco\FactoryMuffin\Kind;

class String extends Kind
{
    public function generate()
    {
        $length = $this->getOption(0, 10);
        return $this->faker->lexify(str_repeat('?', $length));
    }
}
