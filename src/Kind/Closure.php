<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Closure.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Closure extends Kind
{
    /**
     * Return generated data.
     *
     * @return mixed
     */
    public function generate()
    {
        $kind = $this->kind;

        return $kind();
    }
}
