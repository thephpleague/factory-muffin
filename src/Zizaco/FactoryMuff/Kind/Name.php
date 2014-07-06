<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Name extends Kind
{
    public function generate()
    {
        $gender = $this->getOption(0, null);

        return $this->faker->name($gender);
    }
}
