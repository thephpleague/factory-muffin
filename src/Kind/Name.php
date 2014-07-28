<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Name.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Name extends Kind
{
    /**
     * Generate, and return the attribute.
     *
     * @return string
     */
    public function generate()
    {
        $gender = $this->getOption(0, null);

        return $this->faker->name($gender);
    }
}
