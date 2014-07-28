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
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    public function generate()
    {
        $kind = $this->kind;

        return $kind();
    }
}
