<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Date.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Date extends Kind
{
    /**
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    public function generate()
    {
        $format = $this->getOption(0, 'r');

        return $this->faker->date($format);
    }
}
