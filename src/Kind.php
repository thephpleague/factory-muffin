<?php

namespace League\FactoryMuffin;

use Faker\Factory as Faker;

/**
 * Class Kind.
 *
 * @package League\FactoryMuffin
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
abstract class Kind
{
    /**
     * The Kind classes that are available.
     *
     * @type string[]
     */
    protected static $availableKinds = array(
        'call',
        'closure',
        'factory',
        'generic',
    );

    /**
     * The kind of attribute that will be generated.
     *
     * @type string
     */
    protected $kind;

    /**
     * The model instance.
     *
     * @type object
     */
    protected $object;

    /**
     * The faker factory or generator instance.
     *
     * @type \Faker\Factory|\Faker\Generator
     */
    protected $faker;

    /**
     * Initialise our Kind.
     *
     * @param string                          $kind   The kind of attribute that will be generated.
     * @param object                          $object The model instance.
     * @param \Faker\Factory|\Faker\Generator $faker  The faker factory or generator instance.
     */
    public function __construct($kind, $object, $faker)
    {
        $this->kind = $kind;
        $this->object = $object;
        $this->faker = $faker;
    }

    /**
     * Detect the type of Kind we are processing.
     *
     * @param string $kind   The kind of attribute that will be generated.
     * @param object $object The model instance.
     *
     * @return \League\FactoryMuffin\Kind
     */
    public static function detect($kind, $object = null)
    {
        // TODO: Move this somewhere where its only instantiated once
        $faker = new Faker();

        if ($kind instanceof \Closure) {
            return new Kind\Closure($kind, $object, $faker);
        }

        $class = '\\League\\FactoryMuffin\\Kind\\Generic';
        foreach (static::$availableKinds as $availableKind) {
            if (substr($kind, 0, strlen($availableKind)) === $availableKind) {
                $class = '\\League\\FactoryMuffin\\Kind\\' . ucfirst($availableKind);
                break;
            }
        }

        return new $class($kind, $object, $faker->create());
    }

    /**
     * Return an array of all options passed to the Kind (after |).
     *
     * @return array
     */
    public function getOptions()
    {
        $options = explode('|', $this->kind);
        array_shift($options);

        if (count($options) > 0) {
            $options = explode(';', $options[0]);
        }

        return $options;
    }

    /**
     * Returns the name of the kind supplied (exploding at |)
     *
     * @return string
     */
    public function getKind()
    {
        $kind = explode('|', $this->kind);
        return reset($kind);
    }

    /**
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    abstract public function generate();
}
