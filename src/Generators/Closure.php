<?php

namespace League\FactoryMuffin\Generators;

/**
 * Class Closure.
 *
 * @package League\FactoryMuffin\Generator
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Closure extends Base
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
