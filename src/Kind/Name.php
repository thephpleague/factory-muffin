<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Name
 *
 * @package League\FactoryMuffin\Kind
 */
class Name extends Kind
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $gender = $this->getOption(0, null);

        return $this->faker->name($gender);
    }
}
