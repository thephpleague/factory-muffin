<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class String.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class String extends Kind
{
    /**
     * Generate, and return the attribute.
     *
     * @return string
     */
    public function generate()
    {
        $length = $this->getOption(0, 10);
        return $this->faker->lexify(str_repeat('?', $length));
    }
}
