<?php
namespace League\FactoryMuffin\Generators;

/**
 * Interface for generator that require prefix to be declared
 *
 * @author Michael Bodnarchuk <davert@codeception.com>
 */
interface PrefixInterface
{
    /**
     * Return the prefix for current generator
     *
     * @return string
     */
    public static function getPrefix();
}