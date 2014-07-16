<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Closure
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @license MIT
 * @link    https://github.com/thephpleague/factory-muffin/
 */
class Closure extends Kind
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $kind = $this->kind;

        return $kind();
    }
}
