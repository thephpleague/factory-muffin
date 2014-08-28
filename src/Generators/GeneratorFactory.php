<?php

namespace League\FactoryMuffin\Generators;

use Faker\Factory as Faker;

/**
 * This is the generator factory class.
 *
 * @package League\FactoryMuffin\Generators
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class GeneratorFactory
{
    /**
     * The faker instance.
     *
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * The faker localization.
     *
     * @var string
     */
    private $fakerLocale = 'en_EN';

    /**
     * Automatically generate the attribute we want.
     *
     * @param string|callable $kind   The kind of attribute.
     * @param object|null     $object The model instance.
     *
     * @return \League\FactoryMuffin\Generators\GeneratorInterface
     */
    public function generate($kind, $object = null)
    {
        return $this->make($kind, $object)->generate();
    }

    /**
     * Automatically make the generator class we need.
     *
     * @param string|callable $kind   The kind of attribute.
     * @param object|null     $object The model instance.
     *
     * @return \League\FactoryMuffin\Generators\GeneratorInterface
     */
    public function make($kind, $object = null)
    {
        if (is_callable($kind)) {
            return new CallableGenerator($kind, $object);
        }

        if (strpos($kind, 'method|') !== false) {
            return new MethodGenerator($kind, $object);
        }

        if (strpos($kind, 'factory|') !== false) {
            return new FactoryGenerator($kind, $object);
        }

        return new GenericGenerator($kind, $object, $this->getFaker());
    }

    /**
     * Set the faker locale.
     *
     * @param string $local The faker locale.
     *
     * @return $this
     */
    public function setFakerLocale($local)
    {
        $this->fakerLocale = $local;

        $this->faker = null;

        return $this;
    }

    /**
     * Get the faker instance.
     *
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        if (!$this->faker) {
            $this->faker = Faker::create($this->fakerLocale);
        }

        return $this->faker;
    }
}
