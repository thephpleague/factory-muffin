<?php

namespace League\FactoryMuffin\Faker;

use Faker\Factory;

/**
 * This is the faker class.
 *
 * This class is not intended to be used directly, but should be used through
 * the provided facade. The only time where you should be directly calling
 * methods here should be when you're using method chaining after initially
 * using the facade.
 *
 * @package League\FactoryMuffin\Faker
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Faker
{
    /**
     * The generator instance.
     *
     * @var \Faker\Generator
     */
    private $generator;

    /**
     * The faker localization.
     *
     * @var string
     */
    private $locale = 'en_EN';

    /**
     * Create a new instance.
     *
     * @param \Faker\Generator|null $generator
     *
     * @return void
     */
    public function __construct($generator = null)
    {
        if ($generator) {
            $this->generator = $generator;
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

        $this->generator = null;

        return $this;
    }

    /**
     * Get the generator instance.
     *
     * @return \Faker\Generator
     */
    public function getGenerator()
    {
        if (!$this->generator) {
            $this->generator = Factory::create($this->locale);
        }

        return $this->generator;
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
        $this->getGenerator()->addProvider($provider);

        return $this;
    }

    /**
     * Get the providers.
     *
     * @return \Faker\Provider\Base[]
     */
    public function getProviders()
    {
        return $this->getGenerator()->getProviders();
    }

    /**
     * Wrap a faker format in a closure.
     *
     * @param string $formatter
     * @param array  $arguments
     *
     * @return \Closure
     */
    public function format($formatter, $arguments = [])
    {
        $generator = $this->getGenerator();

        return function () use ($generator, $formatter, $arguments) {
            return $generator->format($formatter, $arguments);
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
        return $this->getGenerator()->getFormatter($formatter);
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
        return new static($this->getGenerator()->unique($reset, $maxRetries));
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
        return new static($this->getGenerator()->optional($weight, $default));
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
