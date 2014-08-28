<?php

namespace League\FactoryMuffin\Generators;

use InvalidArgumentException;
use League\FactoryMuffin\Arr;

/**
 * This is the generic generator class.
 *
 * The generic generator will be the generator you use the most. It will
 * communicate with the faker library in order to generate your attribute.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class GenericGenerator implements GeneratorInterface
{
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
     * @param string           $kind   The kind of attribute
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
     * Generate, and return the attribute.
     *
     * We attempt to use Faker for any string passed in. If a Faker property
     * does not exist, we'll return the original string.
     *
     * @return mixed
     */
    public function generate()
    {
        // Only try and use Faker when there are no spaces in the string
        if (!is_string($this->getGenerator()) || strpos($this->getGenerator(), ' ') !== false) {
            return $this->getGenerator();
        }

        try {
            return $this->execute();
        } catch (InvalidArgumentException $e) {
            // If it fails to call it, it must not exist
            return $this->getGenerator();
        }
    }

    /**
     * Call faker to generate the attribute.
     *
     * @return mixed
     */
    private function execute()
    {
        if ($prefix = $this->getPrefix()) {
            $faker = $this->faker->$prefix();
        } else {
            $faker = $this->faker;
        }

        $generator = $this->getGeneratorWithoutPrefix();

        return call_user_func_array(array($faker, $generator), $this->getOptions());
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
     * Returns the prefix to the Generator. This can be "unique", or "optional".
     *
     * @return string
     */
    private function getPrefix()
    {
        $prefixes = array('unique', 'optional');
        $prefix = current(explode(':', $this->getGenerator()));

        return Arr::has($prefixes, $prefix) ? $prefix : false;
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
            return str_replace($prefix.':', '', $this->getGenerator());
        }

        return $this->getGenerator();
    }
}
