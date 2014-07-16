<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Integer
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @license MIT
 * @link    https://github.com/thephpleague/factory-muffin/
 */
class Integer extends Kind
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $length = (int) $this->getOption(0, 5);
        return $this->faker->randomNumber($length, true);
    }
}
