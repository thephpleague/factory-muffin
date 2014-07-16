<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

class Name extends Kind
{
    public function generate()
    {
        $gender = $this->getOption(0, null);

        return $this->faker->name($gender);
    }
}
