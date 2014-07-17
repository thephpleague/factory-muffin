<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Name
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license MIT
 * @link    https://github.com/thephpleague/factory-muffin
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
