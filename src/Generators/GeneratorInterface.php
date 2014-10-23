<?php

namespace League\FactoryMuffin\Generators;

/**
 * This is the generator interface.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
interface GeneratorInterface
{
    /**
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    public function generate();
}
