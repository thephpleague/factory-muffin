<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FactoryMuffin\Generators;

/**
 * This is the generator interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
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
