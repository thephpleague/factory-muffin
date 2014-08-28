<?php

namespace League\FactoryMuffin;

use Faker\Factory;

/**
 * This is the faker class.
 *
 * This class is not intended to be used directly, but should be used through
 * the provided facade. The only time where you should be directly calling
 * methods here should be when you're using method chaining after initially
 * using the facade.
 *
 * @package League\FactoryMuffin
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Faker
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
    private $locale = 'en_EN';

    /**
     * Create a new instance.
     *
     * @param \Faker\Generator|null $faker
     *
     * @return void
     */
    public function __construct($faker = null)
    {
        if ($faker) {
            $this->faker = $faker;
        }
    }

    /**
     * Set the locale.
     *
     * @param string $local The locale.
     *
     * @return $this
     */
    public function setLocale($local)
    {
        $this->locale = $local;

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
            $this->faker = Factory::create($this->locale);
        }

        return $this->faker;
    }

    /**
     * Add a provider.
     *
     * @param \Faker\Provider\Base $provider
     *
     * @return $this
     */
    public function addProvider($provider)
    {
        $this->getFaker()->addProvider($provider);

        return $this;
    }

    /**
     * Get the providers.
     *
     * @return \Faker\Provider\Base[]
     */
    public function getProviders()
    {
        return $this->getFaker()->getProviders();
    }

    /**
     * Wrap a faker format in a closure.
     *
     * @param string $formatter
     * @param array  $arguments
     *
     * @return \Closure
     */
    public function format($formatter, $arguments = array())
    {
        return function () use ($formatter, $arguments) {
            return $this->getFaker()->format($formatter, $arguments);
        };
    }

    /**
     * Get a formatter.
     *
     * @param string $formatter
     *
     * @return \Closure
     */
    public function getFormatter($formatter)
    {
        return $this->getFaker()->getFormatter($formatter);
    }

    /**
     * Make the generated item unique.
     *
     * @param bool $reset
     * @param int  $maxRetries
     *
     * @return static
     */
    public function unique($reset = false, $maxRetries = 10000)
    {
        return new static($this->getFaker()->unique($reset, $maxRetries));
    }

    /**
     * Make the generated item optional.
     *
     * @param float $weight
     * @param mixed $default
     *
     * @return static
     */
    public function optional($weight = 0.5, $default = null)
    {
        return new static($this->getFaker()->optional($weight, $default));
    }

    /**
     * Dynamically wrap faker method calls in closures.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return \Closure
     */
    public function __call($method, $parameters)
    {
        return $this->format($method, $parameters);
    }
}
