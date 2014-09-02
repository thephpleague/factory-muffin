<?php

namespace League\FactoryMuffin;

/**
 * This is the array utilities class.
 *
 * This class contains some array helpers we use internally.
 *
 * @package League\FactoryMuffin
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Arr
{
    /**
     * Get an item from an array.
     *
     * @param array  $array
     * @param string $key
     *
     * @return mixed
     */
    public static function get(&$array, $key)
    {
        if (in_array($key, array_keys($array), true)) {
            return $array[$key];
        }
    }

    /**
     * Is the item in the array.
     *
     * @param array $array
     * @param mixed $item
     *
     * @return bool
     */
    public static function has(&$array, $item)
    {
        return in_array($item, $array, true);
    }

    /**
     * Add an object to an array.
     *
     * @param array  $array
     * @param object $object
     *
     * @return string
     */
    public static function add(&$array, $object)
    {
        $hash = spl_object_hash($object);

        $array[$hash] = $object;

        return $hash;
    }

    /**
     * Remove an object from an array.
     *
     * @param array  $array
     * @param object $object
     *
     * @return void
     */
    public static function remove(&$array, $object)
    {
        if (in_array($object, $array, true)) {
            unset($array[spl_object_hash($object)]);
        }
    }
}
