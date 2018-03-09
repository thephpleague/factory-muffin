<?php

namespace League\FactoryMuffin\HydrationStrategies;

/**
 * Interface for defining strategies to hydrate a model's attributes.
 *
 * @author Florian Dammeyer <factorymuffin@fdms.email>
 */
interface HydrationStrategyInterface
{
    /**
     * Set the attribute with the given key on
     * the given object to the given value.
     *
     * @param object $model The model instance to set the attribute on.
     * @param string $key   The key of the attribute to be set.
     * @param mixed  $value The new value for the given attribute.
     */
    public function set($model, $key, $value);
}
