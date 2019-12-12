<?php

namespace League\FactoryMuffin\HydrationStrategies;

/**
 * A hydration strategy that uses public setter methods
 * or alternatively public property access.
 *
 * This has been the hardcoded way to hydrate models
 * before hydration strategies were implemented.
 *
 * @author Florian Dammeyer <factorymuffin@fdms.email>
 */
class PublicSetterHydrationStrategy implements HydrationStrategyInterface
{
    /**
     * Set the given attribute by using a public setter method
     * or public property access if possible.
     *
     * @param object $model
     * @param string $key
     * @param mixed  $value
     */
    public function set($model, $key, $value)
    {
        $setter = 'set'.ucfirst(static::camelize($key));

        // check if there is a setter and use it instead
        if (method_exists($model, $setter) && is_callable([$model, $setter])) {
            $model->$setter($value);
        } else {
            $model->$key = $value;
        }
    }

    /**
     * Camelize string.
     *
     * Transforms a string to camel case (e.g. first_name -> firstName).
     *
     * @param string $str String in underscore format.
     *
     * @return string
     */
    protected static function camelize($str)
    {
        return preg_replace_callback('/_([a-z0-9])/', function ($c) {
            return strtoupper($c[1]);
        }, $str);
    }
}
