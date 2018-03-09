<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FactoryMuffin;

use League\FactoryMuffin\Exceptions\DefinitionAlreadyDefinedException;
use League\FactoryMuffin\Exceptions\DefinitionNotFoundException;
use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;
use League\FactoryMuffin\Exceptions\ModelNotFoundException;
use League\FactoryMuffin\Generators\GeneratorFactory;
use League\FactoryMuffin\HydrationStrategies\HydrationStrategyInterface;
use League\FactoryMuffin\HydrationStrategies\PublicSetterHydrationStrategy;
use League\FactoryMuffin\Stores\ModelStore;
use League\FactoryMuffin\Stores\StoreInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * This is the factory muffin class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 * @author Patrick McCarren <patrick@mmsc.io>
 * @author Zizaco <zizaco@gmail.com>
 */
class FactoryMuffin
{
    /**
     * The array of model definitions.
     *
     * @var \League\FactoryMuffin\Definition[]
     */
    private $definitions = [];

    /**
     * The store instance.
     *
     * @var \League\FactoryMuffin\Stores\StoreInterface
     */
    protected $store;

    /**
     * The generator factory instance.
     *
     * @var \League\FactoryMuffin\Generators\GeneratorFactory
     */
    protected $factory;

    /**
     * The array of registered hydration strategies.
     *
     * @var \League\FactoryMuffin\HydrationStrategies\HydrationStrategyInterface[]
     */
    private $hydration_strategies = [];

    /**
     * The default hydration strategy instance that will be used if no specialized
     * hydration strategy has been registered for a model class.
     *
     * @var \League\FactoryMuffin\HydrationStrategies\HydrationStrategyInterface
     */
    private $default_hydration_strategy;

    /**
     * Create a new factory muffin instance.
     *
     * @param \League\FactoryMuffin\Stores\StoreInterface|null                          $store                      The store instance.
     * @param \League\FactoryMuffin\Generators\GeneratorFactory|null                    $factory                    The generator factory instance.
     * @param \League\FactoryMuffin\HydrationStrategies\HydrationStrategyInterface|null $default_hydration_strategy The default hydration strategy instance.
     */
    public function __construct(StoreInterface $store = null, GeneratorFactory $factory = null, HydrationStrategyInterface $default_hydration_strategy = null)
    {
        $this->store = $store ?: new ModelStore();
        $this->factory = $factory ?: new GeneratorFactory();
        $this->default_hydration_strategy = $default_hydration_strategy ?: new PublicSetterHydrationStrategy();
    }

    /**
     * Creates and saves multiple versions of a model.
     *
     * Under the hood, we're calling the create method over and over.
     *
     * @param int    $times The number of models to create.
     * @param string $name  The model definition name.
     * @param array  $attr  The model attributes.
     *
     * @return object[]
     */
    public function seed($times, $name, array $attr = [])
    {
        $seeds = [];

        for ($i = 0; $i < $times; $i++) {
            $seeds[] = $this->create($name, $attr);
        }

        return $seeds;
    }

    /**
     * Creates and saves a model.
     *
     * @param string $name The model definition name.
     * @param array  $attr The model attributes.
     *
     * @return object
     */
    public function create($name, array $attr = [])
    {
        $model = $this->make($name, $attr, true);

        $this->store->persist($model);

        if ($this->triggerCallback($model, $name)) {
            $this->store->persist($model);
        }

        return $model;
    }

    /**
     * Trigger the callback if we have one.
     *
     * @param object $model The model instance.
     * @param string $name  The model definition name.
     *
     * @return bool
     */
    protected function triggerCallback($model, $name)
    {
        $callback = $this->getDefinition($name)->getCallback();

        if ($callback) {
            $saved = $this->isPendingOrSaved($model);

            return call_user_func($callback, $model, $saved) !== false;
        }

        return false;
    }

    /**
     * Make an instance of a model.
     *
     * @param string $name The model definition name.
     * @param array  $attr The model attributes.
     * @param bool   $save Are we saving, or just creating an instance?
     *
     * @return object
     */
    protected function make($name, array $attr, $save)
    {
        $definition = $this->getDefinition($name);
        $model = $this->makeClass($definition->getClass(), $definition->getMaker());

        // Make the object as saved so that other generators persist correctly
        if ($save) {
            $this->store->markPending($model);
        }

        // Get the attribute definitions
        $attributes = array_merge($this->getDefinition($name)->getDefinitions(), $attr);

        // Generate and save each attribute for the model
        $this->generate($model, $attributes);

        return $model;
    }

    /**
     * Make an instance of a class.
     *
     * @param string        $class The model class name.
     * @param callable|null $maker The maker callable.
     *
     * @throws \League\FactoryMuffin\Exceptions\ModelNotFoundException
     *
     * @return object
     */
    protected function makeClass($class, callable $maker = null)
    {
        if (!class_exists($class)) {
            throw new ModelNotFoundException($class);
        }

        if ($maker) {
            return call_user_func($maker, $class);
        }

        return new $class();
    }

    /**
     * Is the object saved or will be saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isPendingOrSaved($model)
    {
        return $this->store->isSaved($model) || $this->store->isPending($model);
    }

    /**
     * Delete all the saved models.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public function deleteSaved()
    {
        $this->store->deleteSaved();

        return $this;
    }

    /**
     * Return an instance of the model.
     *
     * This does not save it in the database. Use create for that.
     *
     * @param string $name The model definition name.
     * @param array  $attr The model attributes.
     *
     * @return object
     */
    public function instance($name, array $attr = [])
    {
        $model = $this->make($name, $attr, false);

        $this->triggerCallback($model, $name);

        return $model;
    }

    /**
     * Generate and set the model attributes.
     *
     * @param object $model The model instance.
     * @param array  $attr  The model attributes.
     *
     * @return void
     */
    protected function generate($model, array $attr = [])
    {
        // Get the hydration strategy that has been
        // registered for the given model class
        $hydration_strategy = $this->getHydrationStrategy(get_class($model));

        foreach ($attr as $key => $kind) {
            $value = $this->factory->generate($kind, $model, $this);

            $hydration_strategy->set($model, $key, $value);
        }
    }

    /**
     * Register a hydration strategy instance that will be used
     * to hydrate all models of the given class.
     *
     * @param string                     $name     The class name of the model.
     * @param HydrationStrategyInterface $strategy
     */
    public function setHydrationStrategy($name, HydrationStrategyInterface $strategy)
    {
        $this->hydration_strategies[$name] = $strategy;
    }

    /**
     * Get the hydration strategy for the given model class.
     *
     * If no specific hydration strategy has been registered, the default strategy will be returned.
     *
     * @param string $name
     *
     * @return HydrationStrategyInterface
     */
    public function getHydrationStrategy($name)
    {
        if (array_key_exists($name, $this->hydration_strategies)) {
            return $this->hydration_strategies[$name];
        }

        return $this->default_hydration_strategy;
    }

    /**
     * Get all defined model definitions.
     *
     * @return \League\FactoryMuffin\Definition[]
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Get a model definition.
     *
     * @param string $name The model definition name.
     *
     * @throws \League\FactoryMuffin\Exceptions\DefinitionNotFoundException
     *
     * @return \League\FactoryMuffin\Definition
     */
    public function getDefinition($name)
    {
        if (!isset($this->definitions[$name])) {
            throw new DefinitionNotFoundException($name);
        }

        return $this->definitions[$name];
    }

    /**
     * Define a new model definition.
     *
     * Note that this method cannot be used to modify existing definitions.
     * Please use the getDefinition method for that.
     *
     * @param string $name The model definition name.
     *
     * @throws \League\FactoryMuffin\Exceptions\DefinitionAlreadyDefinedException
     *
     * @return \League\FactoryMuffin\Definition
     */
    public function define($name)
    {
        if (isset($this->definitions[$name])) {
            throw new DefinitionAlreadyDefinedException($name);
        }

        if (strpos($name, ':') !== false) {
            $group = current(explode(':', $name));
            $class = str_replace($group.':', '', $name);
            $this->definitions[$name] = clone $this->getDefinition($class);
            $this->definitions[$name]->setGroup($group);
        } else {
            $this->definitions[$name] = new Definition($name);
        }

        return $this->definitions[$name];
    }

    /**
     * Load the specified factories.
     *
     * This method expects either a single path to a directory containing php
     * files, or an array of directory paths, and will "require" each file.
     * These files should be where you define your model definitions.
     *
     * @param string|string[] $paths The directory path(s) to load.
     *
     * @throws \League\FactoryMuffin\Exceptions\DirectoryNotFoundException
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public function loadFactories($paths)
    {
        foreach ((array) $paths as $path) {
            $real = realpath($path);

            if (!$real) {
                throw new DirectoryNotFoundException($path);
            }

            if (!is_dir($real)) {
                throw new DirectoryNotFoundException($real);
            }

            $this->loadDirectory($real);
        }

        return $this;
    }

    /**
     * Load all the files in a directory.
     *
     * Each required file will have this instance available as "$fm".
     *
     * @param string $path The directory path to load.
     *
     * @return void
     */
    private function loadDirectory($path)
    {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/^[^\.](?:(?!\/\.).)+?\.php$/i');

        $fm = $this;

        foreach ($files as $file) {
            require $file->getPathName();
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
    public static function camelize($str)
    {
        return preg_replace_callback('/_([a-z0-9])/', function ($c) {
            return strtoupper($c[1]);
        }, $str);
    }
}
