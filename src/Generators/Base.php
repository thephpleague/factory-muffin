<?php

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * Class Base.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
abstract class Base
{
    /**
     * The generator classes that are available.
     *
     * @var string[]
     */
    private static $generators = array(
        'call',
        'closure',
        'factory',
    );

    /**
     * The kind of attribute that will be generated.
     *
     * @var string
     */
    protected $kind;

    /**
     * The model instance.
     *
     * @var object
     */
    protected $object;

    /**
     * The faker factory or generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Initialise our Generator.
     *
     * @param string           $kind   The kind of attribute that will be generated.
     * @param object           $object The model instance.
     * @param \Faker\Generator $faker  The faker instance.
     *
     * @return void
     */
    public function __construct($kind, $object, $faker)
    {
        $this->kind = $kind;
        $this->object = $object;
        $this->faker = $faker;
    }

    /**
     * Detect the type of Generator we are processing.
     *
     * @param string           $kind   The kind of attribute that will be generated.
     * @param object           $object The model instance.
     * @param \Faker\Generator $faker  The faker instance.
     *
     * @return \League\FactoryMuffin\Generator
     */
    public static function detect($kind, $object = null, $faker = null)
    {
        if ($kind instanceof \Closure) {
            return new Closure($kind, $object, $faker);
        }

        $class = __NAMESPACE__ . '\\Generic';
        foreach (self::$generators as $generator) {
            if (substr($kind, 0, strlen($generator)) === $generator) {
                $class = __NAMESPACE__ . '\\' . ucfirst($generator);
                break;
            }
        }

        return new $class($kind, $object, $faker);
    }

    /**
     * Return an array of all options passed to the Generator (after |).
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
     * Returns the name of the kind supplied (exploding at |).
     *
     * @return string
     */
    protected function getGenerator()
    {
        $kind = explode('|', $this->kind);
        return reset($kind);
    }

    /**
     * Returns the name of the kind without a prefix.
     *
     * @return string
     */
    protected function getGeneratorWithoutPrefix()
    {
        if ($prefix = $this->getPrefix()) {
            return str_replace($prefix . ':', '', $this->getGenerator());
        }

        return $this->getGenerator();
    }

    /**
     * Returns the prefix to the Generator. This can be "unique", or "optional".
     *
     * @return string
     */
    protected function getPrefix()
    {
        $prefixes = array('unique', 'optional');
        $prefix = current(explode(':', $this->getGenerator()));
        return in_array($prefix, $prefixes) ? $prefix : false;
    }

    /**
     * Create an instance of the model.
     *
     * This model will be automatically saved to the db if
     * the model we are generating it for has been saved.
     *
     * @param string $model Model class name.
     *
     * @return object
     */
    protected function factory($model)
    {
        if (FactoryMuffin::isSaved($this->object)) {
            return FactoryMuffin::create($model);
        }

        return FactoryMuffin::instance($model);
    }

    /**
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    abstract public function generate();
}
