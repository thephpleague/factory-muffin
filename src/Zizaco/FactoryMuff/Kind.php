<?php

namespace Zizaco\FactoryMuff;

use Faker\Factory as Faker;

abstract class Kind
{
    /**
    * The Kind classes that are available.
    * @var array
    */
    protected static $availableKinds = array(
        'call',
        'closure',
        'date',
        'factory',
        'generic',
        'integer',
        'name',
        'string',
        'text',
    );

    /**
    * Holds the Kind we are working on
    * @var Zizaco\FactoryMuff\Kind
    */
    protected $kind = null;

    /**
    * Holds the model data
    * @var array
    */
    protected $model = null;

    /**
    * Initialise our Kind
    * @param Zizaco\FactoryMuff\Kind $kind
    * @param array $model
    */
    public function __construct($kind, $model, $faker)
    {
        $this->kind = $kind;
        $this->model = $model;
        $this->faker = $faker;
    }

    /**
    * Detect the type of Kind we are processing
    * @param  string $kind
    * @param  array $model
    * @return Zizaco\FactoryMuff\Kind
    */
    public static function detect($kind, $model = null)
    {
        // TODO Move this somewhere where its only instantiated once
        $faker = new Faker;

        if ($kind instanceof \Closure) {
            return new Kind\Closure($kind, $model, $faker);
        }

        $class = '\\Zizaco\\FactoryMuff\\Kind\\Generic';
        foreach (static::$availableKinds as $availableKind) {
            if (substr($kind, 0, strlen($availableKind)) === $availableKind) {
                $class = '\\Zizaco\\FactoryMuff\\Kind\\' . ucfirst($availableKind);
                break;
            }
        }

        return new $class($kind, $model, $faker->create());

    }

    /**
    * Returns an option passed to the Kind
    * @param  integer $index
    * @param  mixed $default
    * @return mixed
    */
    public function getOption($index, $default = null)
    {
        $options = $this->getOptions();
        if (isset($options[$index])) {
            return $options[$index];
        }

        return $default;
    }

    /**
    * Return an array of all options passed to the Kind (after |)
    * @return array
    */
    public function getOptions()
    {
        $options = explode('|', $this->kind);
        array_shift($options);

        if (count($options) > 0) {
            $options = explode(',', $options[0]);
        }

        return $options;
    }

    /**
    * Abstract class used by individual Kind's to return
    * generated data
    * @return string|integer
    */
    abstract public function generate();
}
