<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class String extends Kind
{
    public function generate()
    {
        $length = $this->getOption(0, 10);
        return $this->faker->lexify(str_repeat('?', $length));
    }
}
