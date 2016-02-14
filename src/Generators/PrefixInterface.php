<?php

namespace League\FactoryMuffin\Generators;

/**
 * Interface for generator that require prefix to be declared.
 *
 * @author Michael Bodnarchuk <davert@codeception.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
interface PrefixInterface
{
    /**
     * Get the generator prefix.
     *
     * @return string
     */
    public static function getPrefix();
}
