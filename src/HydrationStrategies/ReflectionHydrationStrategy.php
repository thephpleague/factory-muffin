<?php

namespace League\FactoryMuffin\HydrationStrategies;

use ReflectionProperty;

/**
 * A hydration strategy that uses reflection to change properties directly.
 *
 * Reflection enables this strategy to set private and protected properties
 * without the need of public setter methods.
 *
 * @author Florian Dammeyer <factorymuffin@fdms.email>
 */
class ReflectionHydrationStrategy implements HydrationStrategyInterface
{
    public function set($model, $key, $value)
    {
        $property = new ReflectionProperty(get_class($model), $key);
        $property->setAccessible(true);
        $property->setValue($model, $value);
        $property->setAccessible(false);
    }
}
