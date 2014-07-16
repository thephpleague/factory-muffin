<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class String
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @license MIT
 * @link    https://github.com/thephpleague/factory-muffin/
 */
class String extends Kind
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $length = $this->getOption(0, 10);
        return $this->faker->lexify(str_repeat('?', $length));
    }
}
