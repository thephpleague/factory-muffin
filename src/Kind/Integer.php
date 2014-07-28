<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Integer.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Integer extends Kind
{
    /**
     * Generate, and return the attribute.
     *
     * @return int
     */
    public function generate()
    {
        $length = (int) $this->getOption(0, 5);
        return $this->faker->randomNumber($length, true);
    }
}
