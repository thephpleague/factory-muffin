<?php

namespace League\FactoryMuffin\Generators;

/**
 * This is the closure generator class.
 *
 * The closure generator can be used if you want a more custom solution.
 * Whatever you return from the closure you write will be set as the attribute.
 * Please note that class is not be considered part of the public api, and
 * should only be used internally by Factory Muffin.
 *
 * @package League\FactoryMuffin\Generators
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

        return $kind($this->object);
    }
}
